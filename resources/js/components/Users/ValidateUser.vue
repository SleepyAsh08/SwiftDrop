<template>
    <div class="modal fade" id="validate-user">
        <div class="modal-dialog model-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Validate User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <alert-error :form="form"></alert-error>
                    <div class="form-group text-center">
                        <div class="gallery mx-auto d-block pb-0" style="width: 400px; height: 400px;">
                            <div v-viewer="options" class="images clearfix">
                                <template class=" card">
                                    <img :data-source="'/storage/' + formatPhotoPath(form.photos)"
                                        :src="'/storage/' + formatPhotoPath(form.photos)" lass="image"
                                        style="height:400px;">

                                </template>
                            </div>
                        </div>
                    </div>
                    <div v-if="!form.selectedOption">
                        <button type="button" class="btn btn-danger" @click="selectDisapprove">Disapprove</button>
                        <button type="button" class="btn btn-success" @click="selectApprove">Approve</button>
                    </div>
                    <div class="d-inline-block" v-else>
                        <p class="lead">You have selected: <span class="font-weight-bold">{{ form.selectedOption }}
                            </span>
                            <button type="button" class=" btn-sm btn-info" @click="undo">Undo</button>
                        </p>
                    </div>
                    <div v-if="form.selectedOption == 'disapprove'" class="form-group text-center">
                        <h4>Reason for Disapproval</h4>
                        <input type="text" class="form-control" v-model="form.txtdesapproval"
                            placeholder="Enter your reason ">
                    </div>
                    <div v-else>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <!-- <button type="button" class="btn btn-danger" @click="denied">Denied</button> -->
                    <button type="button" class="btn btn-success" v-if="form.selectedOption"
                        @click="submit">Submit</button>
                </div>
            </div>
        </div>
        <!-- declare the edit modal -->
        <!-- <edit-modal :row="selected_user" :page="current_page"></edit-modal> -->
    </div>
</template>

<script>
import EditModal from "./EditUser.vue";
export default {
    props: {
        row: { required: true },
        page: { required: true },
    },
    components: {
        EditModal,
    },
    data() {
        return {
            form: new Form({
                id: '',
                name: '',
                email: '',
                password: '',
                roles: null,
                permissions: null,
                photos: null,
                txtdesapproval: null,
                selectedOption: null,
            }),
            options: {
                toolbar: true,
                url: 'data-source',
                toolbar: true,
                title: true
            },
            submitted: false,
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
        openEditModal(data) {
            this.selected_user = data;
            $('#edit-user').modal('show');
        },
        selectDisapprove() {
            this.form.selectedOption = 'disapprove';
            this.submitted = true;
        },
        selectApprove() {
            this.form.selectedOption = 'approve';
            this.submitted = true;
        },
        undo() {
            this.submitted = false;
            this.form.selectedOption = null;
        },
        handleClick(event) {
            // Your button click logic here
            // Prevent default behavior to stop modal closing
            event.preventDefault();
        },
        submit() {
            if (this.form.selectedOption) {
                Swal.fire({
                    title: 'Are you sure you want to validate the user?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showDenyButton: true,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Validate, This User',
                    denyButtonText: 'Cancel',
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.form.put('api/user/validate/' + this.form.id).then(() => {
                            toast.fire({
                                icon: 'success',
                                text: 'Data Saved.',
                            })
                            $('#validate-user').modal('hide');

                            if (this.form.selectedOption == 'approve') {
                                this.openEditModal(this.form);
                            }
                            this.getData();

                        })
                    }
                }).catch(() => {
                    toast.fire({
                        icon: 'error',
                        text: 'Something went wrong!',
                    })
                });
            } else {
                alert('Please select either Disapprove or Approve');
            }
        }
    },
    watch: {
        row: function () {
            this.form.fill(this.row);
        }
    }
}
</script>
