<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Completed Orders</h1>
                    </div>
                    <div class="col-sm-6">
                        <!-- <ol class="breadcrumb float-sm-right">
                            <button class="nav-link btn" @click="goToAddProducts">
                                <i class="nav-icon fas fa-user-tag"></i>
                                <p>Add Products</p>
                            </button>
                        </ol> -->
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
                                            <th style="width: 10%;">Item Barcode</th>
                                            <th style="width: 10%;">Item Name</th>
                                            <th style="width: 10%;">Courier</th>
                                            <th style="width: 10%;">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(data, index) in option_users.data" :key="index">
                                            <td>{{ data.Item_Barcode }}</td>
                                            <td>{{ data.Item_Name }}</td>
                                            <td>{{ data.courier_name }}</td>
                                            <td>{{ data.status }}</td>
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
                    <!-- <add-modal @getData="getData"></add-modal> -->
                    <!-- declare the edit modal -->
                    <!-- <edit-modal @getData="getData" :row="selected_user" :page="current_page"></edit-modal> -->
                </div>
            </div>
        </div>
    </div>
</template>
<script>

// import EditModal from "./EditProduct.vue";
export default {
    components: {
        // EditModal,
    },
    data() {
        return {
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
        // openAddModal() {
        //     $('#add-user').modal('show');
        // },
        openEditModal(data) {
            this.selected_user = data;
            $('#edit-user').modal('show');
        },
        goToAddProducts() {
            this.$router.push('/ProductsCreate');
        },
        getData(page) {
            if (typeof page === 'undefined' || page.type == 'keyup' || page.type == 'change' || page.type == 'click') {
                page = '/api/product/lists/?page=1';
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
                    axios.delete('/api/product/delete/' + id)
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
        }
    },
    created() {
        this.getData();
    },
}
</script>
<style scoped>
.nav-link.btn {
    background: none;
    border: 1px solid #ccc;
    /* Adjust border color as needed */
    padding: 5px 10px;
    /* Adjust padding as needed */
    font: inherit;
    color: inherit;
    cursor: pointer;
    display: flex;
    align-items: center;
    border-radius: 4px;
    /* Optional: for rounded corners */
    text-align: left;
    /* Ensure text is left-aligned */
}

.nav-link.btn p {
    margin: 0;
    /* Remove default margin from <p> */
    padding-left: 5px;
    /* Add some space between icon and text */
}

.nav-link.btn:hover {
    background-color: #f0f0f0;
    /* Optional: add a hover effect */
}
</style>
