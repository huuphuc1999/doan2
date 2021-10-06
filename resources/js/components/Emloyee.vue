<template>
    <div class="container">
        <div class="row mt-5">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Danh sách nhân viên</h3>

                <div class="card-tools">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#addNew">
                        Thêm mới  <i class="fas fa-user-plus"></i>
                        </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Tên</th>
                      <th>Email</th>
                      <th>Chức vụ</th>
                      <th>Mức lương</th>
                      <th>Ngày vào làm</th>
                      <th>Chỉnh sửa</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(user,index) in users" :key="user.id">
                      <td>{{ index+1 }}</td>
                      <td>{{ user.name }}</td>
                      <td>{{ user.email }}</td>
                      <td>{{ user.role | upText }}</td>
                      <td>{{ user.salary }} VNĐ</td> 
                      <td>{{ user.created_at | myDate }}</td> 
                      <td>
                          <a href="#" @click="editEmloyee(user.id)">
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
            </div>
            <!-- /.card -->
          </div>
        </div>
        <!-- Modal -->
        <form @submit.prevent="addUser">
        <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addNewLabel">Thêm nhân viên mới</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input v-model="form.name" type="text" name="name"
                            placeholder="Nhập tên..."
                            class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                        <div v-if="form.errors.has('name')" v-html="form.errors.get('name')" />
                    </div>

                    <div class="form-group">
                        <input v-model="form.email" type="text" name="email"
                            placeholder="Nhập email..."
                            class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                        <div v-if="form.errors.has('email')" v-html="form.errors.get('email')" />
                    </div>

                    <div class="form-group">
                        <input v-model="form.salary" type="number" name="salary"
                            placeholder="Mức lương..."
                            class="form-control" :class="{ 'is-invalid': form.errors.has('salary') }">  
                        <div v-if="form.errors.has('salary')" v-html="form.errors.get('salary')" />
                    </div>

                    <div class="form-group">
                        <select name="role" v-model="form.role" id="role" class="form-control" :class="{ 'is-invalid': form.errors.has('role') }">
                            <!-- <option value="">Select User Role</option> -->
                            <option value="admin">Admin</option>
                            <option value="emloyee">Nhân viên</option>
                            <option value="accountant">Kế toán</option>
                            <option value="manager">Quản lý</option>
                        </select>
                        <div v-if="form.errors.has('role')" v-html="form.errors.get('role')" />
                    </div>

                    <div class="form-group">
                        <input v-model="form.password" type="password" name="password"
                            placeholder="Mật khẩu..."
                            class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
                        <div v-if="form.errors.has('password')" v-html="form.errors.get('password')" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger" data-dismiss="modal">Trở lại</button>
                    <button type="submit" class="btn btn-primary">Tạo mới</button>
                </div>
                </div>
            </div>
        </div>
        </form>
    </div>
</template>

<script>
import Form from 'vform'
    export default {
      data(){
        return {
          users: {},
          form: new Form({
          name: '',
          role: 'emloyee',
          salary: '',
          email: '',
          password:''
        })
        }
      },
      methods: {
        getUsers(){
          axios.get('api/user')
          .then(
            ( {data} ) => (this.users = data.data));
        },
        addUser(){
          this.form.post('api/user')
          .then( () => {
            Fire.$emit('sendRequest')
            $('#addNew').modal('hide');
            Toast.fire({
            icon: 'success',
            title: 'Thêm nhân viên thành công!'
          })
          .catch( () => {

          })
        })
        },
        deleteEmloyee(id){
          Swal.fire({
            title: 'Chắc chắn xoá?',
            text: "Bạn sẽ không thể khôi phục!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3399FF',
            confirmButtonText: 'Đồng ý, Xoá !'
          }).then((result) => {
            if (result.isConfirmed) {
            this.form.delete('api/user/' + id)
            .then( () => {
                Fire.$emit('sendRequest');
                Swal.fire(    
                  'Đã xoá!',
                  'Nhân viên này đã bị xoá.',
                  'success'
                )
              }
            )
            .catch( () => {
              Swal.fire("Thất bại","Đã có lỗi xảy ra!","warning");
            })
            }
          })
          
        //   Swal.fire({
        //     title: 'Are you sure?',
        //     text: "You won't be able to revert this!",
        //     icon: 'warning',
        //     showCancelButton: true,
        //     confirmButtonColor: '#3085d6',
        //     cancelButtonColor: '#d33',
        //     confirmButtonText: 'Yes, delete it!'
        //   }).then((result) => {

        //     this.form.delete('api/user/' + id)
        //     .then( () => {
        //     if (result.isConfirmed) {
        //       Swal.fire(
        //         'Deleted!',
        //         'Your file has been deleted.',
        //         'success'
        //       )
        //     }
        //     })
        //     .catch( () => {
        //       })
        //   })
        // },
        // editEmloyee(id){

        }
      },
        created(){
          this.getUsers();
          Fire.$on('sendRequest',() =>{
            this.getUsers();
          }); 
        },
    }
</script>
