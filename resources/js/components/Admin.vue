<template>
  <div class="container">
    <div class="row mt-5" v-if="$acl.isAdminOrManager()">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Danh sách chức vụ</h3>

            <div class="card-tools">
              <button class="btn btn-primary" @click="resetModal">
                Thêm mới <i class="fas fa-user-plus"></i>
              </button>
              <button class="btn btn-success" @click="print">Print </button>
            </div>
          </div>
          <!-- /.card-header -->
          <div id="printMe" class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Tên</th>
                  <th>Email</th>
                  <th>Chức vụ</th>
                  <th>Quyền</th>
                  <th>Chỉnh sửa</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(user, index) in users.data" :key="user.id">
                  <td>{{ index + 1 }}</td>
                  <td>{{ user.name }}</td>
                  <td>{{ user.email }}</td>
                  <td>{{ user.role | upText }}</td>
                  <td v-if="user.role ==  roles.admin">Tất cả chức năng</td>
                  <td v-else-if="user.role ==  roles.manager">Quản lý nhân viên, tính lương, In danh sách</td>
                  <td v-else-if="user.role ==  roles.accountant">Tính lương, In danh sách</td>
                  <!-- <td>{{ user.created_at | myDate }}</td> -->
                  <td>
                    <a href="#" @click="fillModal(user)">
                      <i class="fa fa-edit teal"></i>
                    </a>
                    /
                    <a href="#" @click="deleteAdmin(user.id)">
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
    <div v-if="!$acl.isAdmin()">
      <page-404></page-404>
    </div>
    <!-- Modal -->
    <form @submit.prevent="modeOfAdminFunc ? updateAdmin() : addAdmin()">
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
                v-show="modeOfAdminFunc"
                id="addNewLabel"
              >
                Cập nhật người quản lý
              </h5>
              <h5
                class="modal-title"
                v-show="!modeOfAdminFunc"
                id="addNewLabel"
              >
                Thêm người quản lý mới
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
                
                <select
                :required="true"
                  name="role"
                  v-model="form.role"
                  id="role"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('role') }"
                >
                    <option :value="roles.admin">Admin</option>
                    <option :value="roles.manager">Quản lý</option>
                    <option :value="roles.accountant">Kế toán</option>
                </select>
                <div
                  v-if="form.errors.has('role')"
                  v-html="form.errors.get('role')"
                />
              </div>

              <div class="form-group">
                
                <input
                  v-model="form.password"
                  type="password"
                  name="password"
                  placeholder="Mật khẩu..."
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('password') }"
                /><p v-if="modeOfAdminFunc">Mặc định bỏ trống là không đổi </p>
                <div
                  v-if="form.errors.has('password')"
                  v-html="form.errors.get('password')"
                />
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-danger" data-dismiss="modal">
                Trở lại
              </button>
              <button
                v-show="modeOfAdminFunc"
                type="submit"
                class="btn btn-primary"
              >
                Cập nhật
              </button>
              <button
                v-show="!modeOfAdminFunc"
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

export default {
  data() {
    return {
      roles: {
        admin: "admin",
        manager: "manager",
        accountant:"accountant"
      },
      modeOfAdminFunc: false,
      users: {},
      form: new Form({
        id: "",
        name: "",
        role: "accountant",
        email: "",
        password: "",
      }),
    };
  },
  components: {
    Page404,
  },
  methods: {
    print () {
      // Pass the element id here
      this.$htmlToPaper('printMe');
    },
    getAdmin() {
      if (this.$acl.isAdminOrManager()) {
        axios.get("api/admin").then(({ data }) => (this.users = data));
      }
    },
    addAdmin() {
      this.form.post("api/admin").then(() => {
        Fire.$emit("sendRequest");
        $("#addNew").modal("hide");
        Toast.fire({
          icon: "success",
          title: "Thêm admin thành công!",
        })
      });
    },
    deleteAdmin(id) {
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
            .delete("api/admin/" + id)
            .then(() => {
              Fire.$emit("sendRequest");
              Swal.fire("Đã xoá!", "Admin này đã bị xoá.", "success");
            })
            .catch(() => {
              Swal.fire("Thất bại", "Đã có lỗi xảy ra!", "warning");
            });
        }
      });
    },
    updateAdmin() {
      this.form
        .put("api/admin/" + this.form.id)
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
      // this.modeOfAdminFunc = !this.modeOfAdminFunc;
      this.modeOfAdminFunc = false;

      this.form.reset();
      $("#addNew").modal("show");
    },
    fillModal(user) {
      this.modeOfAdminFunc = true;
      this.form.reset();
      $("#addNew").modal("show");
      this.form.fill(user);
    },
    getResults(page = 1) {
      axios.get("api/admin?page=" + page).then((response) => {
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
    this.getAdmin();
    Fire.$on("sendRequest", () => {
      this.getAdmin();
    });
  },
};
</script>
