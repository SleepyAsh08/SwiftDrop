<template>
    <div class="modal fade" id="add-user">
        <div class="modal-dialog model-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <alert-error :form="form"></alert-error>
                    <div class="form-group">
                        <label>Name</label>
                        <input v-model="form.name" type="text" class="form-control">
                        <has-error :form="form" field="name" />
                    </div>
                    <div class="form-group">
                        <label>Last Name</label>
                        <input v-model="form.lastname" type="text" class="form-control">
                        <has-error :form="form" field="lastname" />
                    </div>
                    <div class="form-group">
                        <label>Middle Initial</label>
                        <input v-model="form.middle_initial" type="text" class="form-control">
                        <has-error :form="form" field="middle_initial" />
                    </div>
                    <div class="form-group">
                        <label>Date of Birth</label>
                        <input v-model="form.date_of_birth" type="date" class="form-control">
                        <has-error :form="form" field="date_of_birth" />
                    </div>
                    <div class="form-group">
                        <label>Contact Number</label>
                        <input v-model="form.contact_number" type="number" class="form-control">
                        <has-error :form="form" field="contact_number" />
                    </div>
                    <div class="form-group">
                        <label>Telephone Number</label>
                        <input v-model="form.telephone_number" type="number" class="form-control">
                        <has-error :form="form" field="telephone_number" />
                    </div>
                    <div class="form-group">
                        <label>Email address</label>
                        <input v-model="form.email" type="email" class="form-control">
                        <has-error :form="form" field="email" />
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input v-model="form.password" type="password" class="form-control">
                        <has-error :form="form" field="password" />
                    </div>

                    <div class="form-group">
                        <label>Upload Supporting Information</label>
                        <input type="file" @change="onFileChange" required multiple class="form-control">
                        <has-error :form="form" field="photos" />
                    </div>

                    <div class="form-group">
                        <span class="ml-5 text-danger">You are Buyer provide your valid Id</span><br>
                        <span class="ml-5 text-danger">You are Seller provide your Business Permit</span>

                    </div>
                    <div v-if="can('approve user')" class="form-group">
                        <label>Role</label>
                        <multiselect v-model="form.role" :options="option_roles" :multiple="false"
                            :close-on-select="true" :clear-on-select="false" :preserve-search="true"
                            placeholder="Pick some" label="name" track-by="name" :preselect-first="true"
                            @input="selectRole">
                        </multiselect>
                        <has-error :form="form" field="guard_name" />

                    </div>
                    <div v-if="can('approve user')" class="form-group">
                        <label>Permission</label>
                        <multiselect v-model="form.permissions" :options="option_permissions" :multiple="true"
                            :close-on-select="false" :clear-on-select="false" :preserve-search="true"
                            placeholder="Pick some" label="name" track-by="name" :preselect-first="true">
                        </multiselect>
                        <has-error :form="form" field="guard_name" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" @click="create">Save</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            form: new Form({
                id: '',
                name: '',
                lastname: '',
                middle_initial: '',
                date_of_birth: '',
                contact_number: '',
                telephone_number: '',
                email: '',
                password: '',
                photos: '',
                user_photo: '',
                roles: null,
                permissions: null,
            }),
            photos: [],
            option_permissions: [],
            option_roles: [],
        }
    },
    methods: {
        onFileChange(e) {
            this.photos = Array.from(e.target.files);
        },
        selectRole() {
            this.form.permissions = this.form.role.permissions;
        },
        create() {
            const formData = new FormData();
            formData.append('id', this.form.id);
            formData.append('name', this.form.name);
            formData.append('lastname', this.form.lastname);
            formData.append('middle_initial', this.form.middle_initial);
            formData.append('date_of_birth', this.form.date_of_birth);
            formData.append('contact_number', this.form.contact_number);
            formData.append('telephone_number', this.form.telephone_number);
            formData.append('email', this.form.email);
            formData.append('password', this.form.password);
            formData.append('roles', this.form.roles);
            formData.append('permissions', this.form.permissions);

            // Append each selected photo file to the formData
            this.photos.forEach((photo, index) => {
                formData.append(`photos[${index}]`, photo);
            });

            this.form.post('/api/user/create', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then(() => {
                toast.fire({
                    icon: 'success',
                    text: 'Data Saved.',
                })
                this.form.reset();
                this.photos = [];
                this.$emit('getData');// call method from parent
                $('#add-user').modal('hide');

            }).catch(() => {
                toast.fire({
                    icon: 'error',
                    text: 'Something went wrong!',
                })
            });
        },
        loadPermissions() {
            axios.get('/api/permission/all')
                .then(response => {
                    this.option_permissions = response.data.data;
                });
        },
        loadRoles() {
            axios.get('/api/role/all')
                .then(response => {
                    this.option_roles = response.data.data;
                });
        },
    },
    mounted() {
        this.loadPermissions();
        this.loadRoles();
    }
}
</script>
