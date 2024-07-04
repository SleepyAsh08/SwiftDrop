<template>
    <div class="modal fade" id="edit-user">
        <div class="modal-dialog model-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <alert-error :form="form"></alert-error>
                    <div class="form-group">
                        <label>Upload User photo</label>
<<<<<<< HEAD
                        <input ref="userPhotoInput" @change="handleFileChange" type="file" class="form-control"
                            required>
                         <has-error :form="form" field="user_photo" />
                </div> -->
=======
                        <input type="file" @change="onFileChange" required multiple class="form-control">
                        <has-error :form="form" field="user_photo" />
                    </div>
>>>>>>> 669de446e1ccc8bb561c34c6e3a865d323b22c0b
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
                    <div v-if="can('approve user')" class="form-group">
                        <label>Role</label>
                        <multiselect v-model="form.roles" :options="option_roles" :multiple="false"
                            :close-on-select="true" :clear-on-select="false" :preserve-search="true"
                            placeholder="Pick some" label="name" track-by="name" :preselect-first="true"
                            @input="selectRole">
                        </multiselect>
                        <has-error :form="form" field="roles" />

                    </div>

                    <div class="form-group">
                        <label>Upload Profile</label>
                        <input type="file" @change="onFileChange" multiple class="form-control">
                    </div>


                    <div v-if="can('approve user')" class="form-group">
                        <label>Permission</label>
                        <multiselect v-model="form.permissions" :options="option_permissions" :multiple="true"
                            :close-on-select="false" :clear-on-select="false" :preserve-search="true"
                            placeholder="Pick some" label="name" track-by="name" :preselect-first="true">
                        </multiselect>
                        <has-error :form="form" field="permissions" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" @click="update">Save changes</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        row: { required: true },
        page: { required: true },
    },
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
                roles: null,
                permissions: null,
            }),
<<<<<<< HEAD
            user_photo: [],
=======
            photos: [],
>>>>>>> 669de446e1ccc8bb561c34c6e3a865d323b22c0b
            option_permissions: [],
            option_roles: [],
            options: {
                toolbar: true,
                url: 'data-source',
                toolbar: true,
                title: true
            },
        }
    },
    methods: {
<<<<<<< HEAD
        // handleFileChange(event) {
        //     if (event.target.files.length === 0) {
        //         // Handle no file selected case (optional: display an error message)
        //         return;
        //     }

        //     const file = event.target.files[0]; // Get the first selected file
        //     this.form.user_photo = file;
        // },
        onFileChange(e) {
            this.user_photo = Array.from(e.target.files);
        },

        formatPhotoPath(photoPath) {
            if (photoPath) {
                return photoPath.replace(/^\["(.+)"\]$/, '$1');
            } else {
                return '';
            }
=======
        onFileChange(e) {
            this.photos = Array.from(e.target.files);
>>>>>>> 669de446e1ccc8bb561c34c6e3a865d323b22c0b
        },
        selectRole() {
            this.form.permissions = this.form.roles.permissions;
        },
        update() {
<<<<<<< HEAD

            const formData = new FormData();

            // formData.append('name', this.form.name);
=======
            const formData = new FormData();
            formData.append('id', this.form.id);
            formData.append('name', this.form.name);
>>>>>>> 669de446e1ccc8bb561c34c6e3a865d323b22c0b
            formData.append('lastname', this.form.lastname);
            formData.append('middle_initial', this.form.middle_initial);
            formData.append('date_of_birth', this.form.date_of_birth);
            formData.append('contact_number', this.form.contact_number);
            formData.append('telephone_number', this.form.telephone_number);
            formData.append('email', this.form.email);
            formData.append('password', this.form.password);
<<<<<<< HEAD

            // Append each photo to the FormData
            this.user_photo.forEach((photo, index) => {
                formData.append(`user_photo[${index}]`, photo);
            });



            // console.log(this.user_photo);
            axios.put(`api/user/update/${this.form.id}`, formData, {
=======
            formData.append('roles', this.form.roles);
            formData.append('permissions', this.form.permissions);

            // Append each selected photo file to the formData
            this.photos.forEach((photo, index) => {
                formData.append(`photos[${index}]`, photo);
            });

            this.form.put('/api/user/update', formData, {
>>>>>>> 669de446e1ccc8bb561c34c6e3a865d323b22c0b
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then(() => {
                toast.fire({
                    icon: 'success',
                    text: 'Data Saved.',
                });
                this.$emit('getData', this.page);
                $('#edit-user').modal('hide');
            }).catch(error => {
                toast.fire({
                    icon: 'error',
                    text: error.message,
                });
            });




            // const formData = new FormData();
            // formData.append('user_photo', this.form.user_photo);


            // this.form.put('api/user/update/' + this.form.id, {
            // }).then(() => {
            //     toast.fire({
            //         icon: 'success',
            //         text: 'Data Saved.',
            //     })
            //     //"page" maintain selected page in the parent page
            //     this.$emit('getData', this.page);// call method from parent (reload data table)
            //     $('#edit-user').modal('hide');
            // }).catch(error => {
            //     toast.fire({
            //         icon: 'error',
            //         text: error.message,
            //     })
            // });
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
    watch: {
        row: function () {
            this.form.fill(this.row);
            // console.log(this.row);
        }
    },
    mounted() {
        this.loadPermissions();
        this.loadRoles();
    }
}
</script>
