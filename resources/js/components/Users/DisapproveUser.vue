<template>
    <div class="modal fade" id="disapprove-user" data-bs-backdrop="static" tabindex="-1" aria-labelledby="modal2Label"
        aria-hidden="true">
        <div class="modal-dialog model-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Disapprove User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <alert-error :form="form"></alert-error>
                    <div class="form-group text-center">
                        <h4>Reason for Disapproval</h4>
                        <input type="text" class="form-control" v-model="form.txtdesapproval"
                            placeholder="Click and Enter your reason ">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" @click="selectDisapprove">Disapprove</button>

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
    components: {
        // EditModal,
    },
    data() {
        return {
            form: new Form({
                id: '',
                name: '',
                middle_initial: '',
                lastname: '',
                date_of_birth: '',
                contact_number: '',
                telephone_number: '',
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
        selectDisapprove() {
            this.form.selectedOption = 'disapprove';
            Swal.fire({
                title: 'Are you sure you want to disapprove the user?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showDenyButton: true,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Yes',
                denyButtonText: 'Cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    this.form.put('api/user/validate/' + this.form.id).then(() => {
                        toast.fire({
                            icon: 'success',
                            text: 'Data Saved.',
                        })

                        $('#validate-user').modal('hide');
                        $('#disapprove-user').modal('hide');
                        window.location.reload();
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
    watch: {
        row: function () {
            this.form.fill(this.row);
        }
    }
}
</script>
