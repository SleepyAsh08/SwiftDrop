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
                                        <select v-model="filter" @change="getData"
                                            class="form-control form-control-sm pr-3 input-group-append bg-white">
                                            <option value="All">All</option>
                                            <option value="Deactivate">Deactivate</option>
                                            <option value="Activate">Activate</option>
                                        </select>
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
                                <table class="table table-head-fixed text-nowrap text-center">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Document</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Contact Number</th>
                                            <th>Type of User</th>
                                            <th>Date of Validation</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <tr v-for="(data, index) in         option_users.data        " :key="index">
                                            <td>{{ data.id }}</td>
                                            <td><img v-if="data.photos && data.photos.length"
                                                    :src="'/storage/'+formatPhotoPath(data.photos)" alt="Product Photo"
                                                    style="max-width: 200px; max-height: 200px;"></td>
                                            <!-- <td>{{ data }}</td> -->
                                            <td>{{ data.name + " " + data.lastname}}</td>
                                            <td>{{ data.email }}</td>

                                            <td>{{ data.contact_number }}</td>
                                            <td v-if="data.roles.length > 0">
                                                <span v-for="role in data.roles" :key="role.id">
                                                    {{ role.name }}
                                                </span>
                                            </td>
                                            <td v-else-if="data.reason_of_disapproval != null">
                                                <span class="badge badge-danger">
                                                    This user is denied
                                                </span>
                                            </td>
                                            <td v-else>
                                                <span class="badge badge-danger">
                                                    No User Type for Evaluation and
                                                    Approval
                                                </span>
                                            </td>
                                            <td v-if="data.reason_of_disapproval != null && data.approved_at == NULL">
                                                <span class="badge badge-danger">
                                                    This user is denied
                                                </span>
                                            </td>
                                            <td v-else-if="data.approved_at == NULL">
                                                <span class="badge badge-danger">
                                                    Not Validated
                                                </span>
                                            </td>
                                            <td v-else>
                                                {{ data.approved_at }}
                                            </td>

                                            <td class="text-right">

                                                <button
                                                    v-if="data.approved_at === null && data.reason_of_disapproval === null"
                                                    type="button" class="btn btn-success btn-sm"
                                                    @click="openValidateModal(data)"><i class="fas fa-search"></i>
                                                    Validate
                                                </button>
                                                <button
                                                    v-if="data.approved_at != null && data.reason_of_disapproval === null"
                                                    type="button" class="btn btn-primary btn-sm"
                                                    @click="openEditModal(data)"><i class="fas fa-edit"></i>
                                                    Assign Permission</button>
                                                <button v-if="data.deleted_at === null && can('delete user')"
                                                    type="button" class="btn btn-danger btn-sm"
                                                    @click="remove(data.id)"><i class="fas fa-ban"></i> Deactivate
                                                </button>
                                                <button v-if="data.deleted_at != null && can('delete user')"
                                                    type="button" class="btn btn-success btn-sm"
                                                    @click="activate(data.id)"><i class="fas fa-check"></i> Activate
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <ul class="pagination pagination-sm m-1 float-right">
                                    <li class="page-item" v-for="(link, index) in option_users.links" :key="index">
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
                    <!-- declare the approve modal -->
                    <validate-modal @getData="getData" :row="selected_user" :page="current_page"></validate-modal>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import addModal from "./AddUser.vue";
import EditModal from "./AssignUser.vue";
import ValidateModal from "./ValidateUser.vue";
export default {
    components: {
        addModal,
        EditModal,
        ValidateModal,
    },
    data() {
        return {
            option_users: [],
            length: 10,
            search: '',
            filter: 'All',
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
        formatPhotoPath(photoPath) {
            if (photoPath) {
                return photoPath.replace(/^\["(.+)"\]$/, '$1');
            } else {
                return '';
            }
        },
        openAddModal() {
            $('#add-user').modal('show');
        },
        openEditModal(data) {
            this.selected_user = data;
            $('#assign-user').modal('show');
        },
        openValidateModal(data) {
            this.selected_user = data;
            $('#validate-user').modal('show');
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
                        filter: this.filter,
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
                confirmButtonText: 'Yes, Deactivate this account!',
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete('/api/user/delete/' + id)
                        .then(response => {
                            Swal.fire(
                                'Disable!',
                                'Your file has been Deactivated.',
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
        activate(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Activate this account!',
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.put('/api/user/activate/' + id)
                        .then(response => {
                            Swal.fire(
                                'Enabled!',
                                'Your file has been Activated.',
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
    },
    created() {
        this.getData();
    },
}
</script>
