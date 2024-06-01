<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Add Measurement</div>

                    <div class="card-body">
                        <alert-error :form="form"></alert-error>
                        <form method="POST">
                            <div class="form-group">
                                <label>Measurement</label>
                                <input v-model="form.measurement" type="text" class="form-control">
                                <has-error :form="form" field="Measurement" />
                            </div>
                            <!-- <div class="form-group required">
                                <label>Course</label>
                                <multiselect v-model="form.course_id" placeholder="Search Course" label="course"
                                    track-by="id" :options="option_course" :multiple="false" :close-on-select="true"
                                    :clear-on-select="false"></multiselect>
                                <has-error :form="form" field="course_id.id" />
                            </div> -->
                            <button type="button" class="btn btn-primary" @click="create">Save</button>
                        </form>
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
                measurement: '',
            }),
        }
    },
    methods: {
        create() {
            this.form.post('/api/measurement/create').then(() => {
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
        loadCourse() {
            axios.get('/api/course/all')
                .then(response => {
                    this.option_course = response.data.data;
                });

        },
    },
    mounted() {
        this.loadCourse();
    }
}
</script>
