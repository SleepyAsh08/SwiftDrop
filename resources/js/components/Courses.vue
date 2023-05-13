<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Course Information</div>

                    <div class="card-body">
                        <alert-error :form="form"></alert-error>
                        <div class="form-group">
                            <label>Course</label>
                            <input v-model="form.course" type="text" class="form-control">
                            <has-error :form="form" field="course" />
                        </div>
                        <div class="form-group">
                            <label>Limit</label>
                            <input v-model="form.limit" type="number" class="form-control">
                            <has-error :form="form" field="limit" />
                        </div>
                        <div class="form-group">
                            <label>Minimum GPA</label>
                            <input v-model="form.min_GPA" type="number" class="form-control">
                            <has-error :form="form" field="min_GPA" />
                        </div>
                        <div class="form-group">
                            <label>Must Be Scholar</label>
                            <input v-model="form.is_scholar" type="checkbox" :true-value="1" :false-value="0">
                            <has-error :form="form" field="is_scholar" />
                        </div>
                        <button type="button" class="btn btn-primary" @click="create">Save</button>
                    </div>
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
                course: '',
                limit: '',
                min_GPA: '',
                is_scholar: '',
            }),
        }
    },
    methods: {
        create() {
            this.form.post('/api/create/course').then(() => {
                toast.fire({
                    icon: 'success',
                    text: 'Data Saved.',
                })
                this.form.reset();
            }).catch(() => {
                toast.fire({
                    icon: 'error',
                    text: 'Something went wrong!',
                })
            });
        },
    },
}
</script>
