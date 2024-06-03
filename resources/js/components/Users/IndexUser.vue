<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Users</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Users</a></li>
                            <!-- <li class="breadcrumb-item active">Starter Page</li> -->
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="content">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header p-3">
                                <h3 class="card-title"> </h3>
                                <div class="card-tools float-left">
                                    <div class="input-group input-group-sm">
                                        <select v-model="length" @change="getData" class="form-control form-control-sm">
                                            <option value="10">10</option>
                                            <option value="25">25</option>
                                            <option value="30">30</option>
                                        </select>
                                        <button class="btn btn-success btn-sm ml-auto" @click="openAddModal"
                                            v-if="can('create user')"><i class="fas fa-user-plus"></i> Add</button>
                                    </div>
                                </div>
                                <div class="card-tools">
                                    <div class="input-group input-group-sm">
                                        <input v-model="search" type="text" @keyup="getData" name="table_search"
                                            class="form-control float-right" placeholder="Search" />
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-primary" @click="getData">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body table-responsive p-0">
                                <table class="table table-head-fixed text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Type of User</th>
                                            <th class="text-center">Status</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(data, index) in         option_users.data        " :key="index">
                                            <td>{{ data.id }}</td>
                                            <td>{{ data.name }}</td>
                                            <td>{{ data.email }}</td>
                                            <td v-if="data.roles.length > 0">
                                                <span v-for="role in data.roles" :key="role.id">
                                                    {{ role.name }}
                                                </span>
                                            </td>
                                            <td v-else>
                                                <span class="badge badge-danger">
                                                    No User Type
                                                </span>
                                            </td>

                                            <td v-if="data.roles.length > 0" class="text-center">
                                            </td>
                                            <td v-else class="text-center">
                                                <span class=" badge badge-danger text-center">For Evaluation and
                                                    Approval</span>
                                                <!-- <img v-if="data.photos && data.photos.length"
                                                    :src="'/storage/' + formatPhotoPath(data.photos)"
                                                    alt="Personal Info Photo"
                                                    style="max-width: 200px; max-height: 200px;"> -->

                                                <div class="gallery mx-auto d-block pb-0"
                                                    style="width: 100px; height: 100px;">
                                                    <div v-viewer="options" class="images clearfix">
                                                        <template class=" card">
                                                            <img :data-source="'/storage/product_photos/ZGSjw3J0j27Oc7SJxp1Qhe7R2T9kRjqItNUSRj4s.png'"
                                                                :src="'/storage/product_photos/ZGSjw3J0j27Oc7SJxp1Qhe7R2T9kRjqItNUSRj4s.png'"
                                                                lass="image" :alt="'/images/0_noimage.jpg'"
                                                                style="height:100px;">

                                                        </template>
                                                    </div>
                                                </div>
                                            </td>


                                            <td class="text-right">
                                                <button v-if="data.roles.length == empty" type="button"
                                                    class="btn btn-success btn-sm" @click="approve(data.id)"><i
                                                        class="fas fa-check"></i> Approve</button>
                                                <button type="button" class="btn btn-primary btn-sm"
                                                    @click="openEditModal(data)" v-if="can('edit user')"><i
                                                        class="fas fa-edit"></i> Edit</button>
                                                <button type="button" class="btn btn-danger btn-sm"
                                                    @click="remove(data.id)" v-if="can('delete user')"><i
                                                        class="fas fa-trash-alt"></i> Remove</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <ul class="pagination pagination-sm m-1 float-right">
                                    <li class="page-item"
                                        v-for="(       link, index       ) in        option_users.links       "
                                        :key="index">
                                        <button v-html="link.label" @click="getData(link.url)" class="page-link"
                                            :disabled="link.url == null || link.active"
                                            :class="{ 'text-muted': link.url == null || link.active }">
                                        </button>
                                    </li>
                                </ul>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- declare the add modal -->
                    <add-modal @getData="getData"></add-modal>
                    <!-- declare the edit modal -->
                    <edit-modal @getData="getData" :row="selected_user" :page="current_page"></edit-modal>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import addModal from "./AddUser.vue";
import EditModal from "./EditUser.vue";
export default {
    components: {
        addModal,
        EditModal,
    },
    data() {
        return {
            options: {
                toolbar: true,
                url: 'data-source',
                toolbar: true,
                title: true
            },
            option_users: [],
            length: 10,
            search: '',
            showSchedule: false,
            is_searching: true,
            selected_user: [],
            current_page: [],
            form: new Form({
                id: '',
            }),
            error: '',
        }
    },
    methods: {
        openAddModal() {
            $('#add-user').modal('show');
        },
        openEditModal(data) {
            this.selected_user = data;
            $('#edit-user').modal('show');
        },
        getData(page) {
            if (typeof page === 'undefined' || page.type == 'keyup' || page.type == 'change' || page.type == 'click') {
                page = '/api/user/list/?page=1';
            }
            this.current_page = page;
            if (this.timer) {
                clearTimeout(this.timer);
                this.timer = null;
            }
            this.timer = setTimeout(() => {
                axios.get(page, {
                    params: {
                        search: this.search,
                        length: this.length,
                        time_start: this.time_start,
                        time_end: this.time_end,
                        day: this.day,
                        section_id: this.section_id,
                    },
                })
                    .then(response => {
                        if (response.data.data) {
                            this.option_users = response.data.data;
                        }
                    }).catch(error => {
                        this.error = error;
                        toast.fire({
                            icon: 'error',
                            text: error.response.data.message,
                        })
                    });
            }, 500);
        },
        remove(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete('/api/user/delete/' + id)
                        .then(response => {
                            Swal.fire(
                                'Deleted!',
                                'Your file has been deleted.',
                                'success'
                            )
                            this.getData();
                        })
                }
            }).catch(() => {

                toast.fire({
                    icon: 'error',
                    text: 'Something went wrong!',
                })
            });
        },
        approve(id) {
            Swal.fire({
                title: 'Are you sure you want to approve the user?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                showDenyButton: true,
                confirmButtonColor: '#3085d6', // buyer
                cancelButtonColor: '#3085d6',   // Seller
                denyButtonTextColor: '#3085d6',
                cancelButtonText: 'Approved as Seller',
                confirmButtonText: 'Approved as Buyer',
                denyButtonText: 'Cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    // axios.delete('/api/user/delete/' + id)
                    //     .then(response => {
                    toast.fire({
                        icon: 'success',
                        text: 'Data Saved.',
                    })
                    //         this.getData();
                    //     })
                }
                else if (result.dismiss) {
                    // axios.delete('/api/user/delete/' + id)
                    //     .then(response => {
                    toast.fire({
                        icon: 'success',
                        text: 'Data Saved.',
                    })
                    //         this.getData();
                    //     })
                }
            }).catch(() => {
                toast.fire({
                    icon: 'error',
                    text: 'Something went wrong!',
                })
            });
        }
    },
    created() {
        this.getData();
    },
}
</script>
