<template>
  <div class="container">
    <div class="row mt-5" v-if="$acl.isAdminOrManager()">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Danh sách nhân viên</h3>

            <div class="card-tools">
              <button class="btn btn-primary" @click="resetModal">
                Thêm mới <i class="fas fa-user-plus"></i>
              </button>
              <button class="btn btn-success" @click="print">Print </button>
            </div>
          </div>
          <!-- /.card-header -->
          <div  id="printMe" class="card-body table-responsive p-0">
            <table  class="table table-hover text-nowrap">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Tên</th>
                  <th>Email</th>
                  <th>QR CODE</th>
                  <th>Mức lương</th>
                  <th>Ngày vào làm</th>
                  <th>Chỉnh sửa</th>
                </tr>
              </thead>
              <tbody >
                <tr  v-for="(user, index) in users.data" :key="user.id">
                  <td>{{ index + 1 }}</td>
                  <td>{{ user.name }}</td>
                  <td>{{ user.email }}</td>
                   <td>
                     <qr-code 
                    style="padding: 10px"
                    :text="user.qrcode"
                    :size="size"
                    error-level="L"
                    ></qr-code>
                     </td> 
                 
                  <td>{{ user.salary }} VNĐ</td>
                  <td>{{ user.created_at | myDate }}</td>
                  <td>
                    <a href="#" @click="fillModal(user)">
                      <i class="fa fa-edit teal"></i>
                    </a>
                    /
                    <a href="#" @click="deleteEmloyee(user.id)">
                      <i class="fa fa-trash red"></i>
                    </a>
                  </td>
               
                </tr>
              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
          <div class="card-footer">
            <pagination
              :data="users"
              @pagination-change-page="getResults"
            ></pagination>
          </div>
        </div>
        <!-- /.card -->
      </div>
    </div>
    <div v-if="!$acl.isAdminOrManager()">
      <page-404></page-404>
    </div>
    <!-- Modal -->
    <form @submit.prevent="modeOfEmloyeeFunc ? updateEmloyee() : addEmloyee()">
      <div
        class="modal fade"
        id="addNew"
        tabindex="-1"
        role="dialog"
        aria-labelledby="addNewLabel"
        aria-hidden="true"
      >
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5
                class="modal-title"
                v-show="modeOfEmloyeeFunc"
                id="addNewLabel"
              >
                Cập nhật nhân viên
              </h5>
              <h5
                class="modal-title"
                v-show="!modeOfEmloyeeFunc"
                id="addNewLabel"
              >
                Thêm nhân viên mới
              </h5>
              <button
                type="button"
                class="close"
                data-dismiss="modal"
                aria-label="Close"
              >
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <input
                  v-model="form.name"
                  type="text"
                  name="name"
                  placeholder="Nhập tên..."
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('name') }"
                />
                <div
                  v-if="form.errors.has('name')"
                  v-html="form.errors.get('name')"
                />
              </div>

              <div class="form-group">
                <input
                  v-model="form.email"
                  type="text"
                  name="email"
                  placeholder="Nhập email..."
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('email') }"
                />
                <div
                  v-if="form.errors.has('email')"
                  v-html="form.errors.get('email')"
                />
              </div>

              <div class="form-group">
                <input
                  v-model="form.salary"
                  type="number"
                  name="salary"
                  placeholder="Mức lương..."
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('salary') }"
                />
                <div
                  v-if="form.errors.has('salary')"
                  v-html="form.errors.get('salary')"
                />
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-danger" data-dismiss="modal">
                Trở lại
              </button>
              <button
                v-show="modeOfEmloyeeFunc"
                type="submit"
                class="btn btn-primary"
              >
                Cập nhật
              </button>
              <button
                v-show="!modeOfEmloyeeFunc"
                type="submit"
                class="btn btn-primary"
              >
                Tạo mới
              </button>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
import Form from "vform";
import Page404 from "./404Page";
import VueQRCodeComponent from 'vue-qr-generator'
export default {
  data() {
    return {
      

      size: 70,
      modeOfEmloyeeFunc: false,
      users: {},
      form: new Form({
        id: "",
        name: "", 
        qrcode: "",
        salary: "", 
        email: "",
      }),

    };
  },
  components: {
    Page404,
    'qr-code':VueQRCodeComponent
  },
  methods: {
    print () {
      // Pass the element id here
      this.$htmlToPaper('printMe');
    },
    getEmloyee() {
      if (this.$acl.isAdminOrManager()) {
        axios.get("api/user").then(({ data }) => (this.users = data));
      }
    },
    addEmloyee() {
      this.form.post("api/user").then(() => {
        Fire.$emit("sendRequest");
        $("#addNew").modal("hide");
        Toast.fire({
          icon: "success",
          title: "Thêm nhân viên thành công!",
        })
      });
    },
    deleteEmloyee(id) {
      Swal.fire({
        title: "Chắc chắn xoá?",
        text: "Bạn sẽ không thể khôi phục!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3399FF",
        confirmButtonText: "Đồng ý, Xoá !",
      }).then((result) => {
        if (result.isConfirmed) {
          this.form
            .delete("api/user/" + id)
            .then(() => {
              Fire.$emit("sendRequest");
              Swal.fire("Đã xoá!", "Nhân viên này đã bị xoá.", "success");
            })
            .catch(() => {
              Swal.fire("Thất bại", "Đã có lỗi xảy ra!", "warning");
            });
        }
      });
    },
    updateEmloyee() {
      this.form
        .put("api/user/" + this.form.id)
        .then(() => {
          // success
          $("#addNew").modal("hide");
          Swal.fire(
            "Đã cập nhật!",
            "Cập nhật thông tin thành công.",
            "success"
          );
          Fire.$emit("sendRequest");
        })
        .catch(() => {});
    },
    resetModal() {
      // this.modeOfEmloyeeFunc = !this.modeOfEmloyeeFunc;
      this.modeOfEmloyeeFunc = false;

      this.form.reset();
      $("#addNew").modal("show");
    },
    fillModal(user) {
      this.modeOfEmloyeeFunc = true;
      this.form.reset();
      $("#addNew").modal("show");
      this.form.fill(user);
    },
    getResults(page = 1) {
      axios.get("api/user?page=" + page).then((response) => {
        this.users = response.data;
      });
    },
  },
  created() {
    Fire.$on('startSearch',() => {
      let query = this.$parent.search;
      axios.get('api/searchEmloyee?q=' + query)
      .then((data) => {
        this.users = data.data;
        
      })
      .catch(() => {

      })
    })
    this.getEmloyee();
    Fire.$on("sendRequest", () => {
      this.getEmloyee();
    });
  },
};
</script>
<style scoped>
table td {
  vertical-align: middle !important;
}
</style>
