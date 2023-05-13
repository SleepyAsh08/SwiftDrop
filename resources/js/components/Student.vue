<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Student Information</div>

                    <div class="card-body">
                        <alert-error :form="form"></alert-error>
                        <form method="POST">
                            <div class="form-group">
                                <label>Student Name</label>
                                <input v-model="form.name" type="text" class="form-control">
                                <has-error :form="form" field="name" />
                            </div>
                            <div class="form-group">
                                <label>Scholarship</label>
                                <input v-model="form.scholarship" type="text" class="form-control">
                                <has-error :form="form" field="scholarship" />
                            </div>
                            <div class="form-group">
                                <label>Grade Point Average (GPA)</label>
                                <input v-model="form.GPA" type="number" class="form-control">
                                <has-error :form="form" field="GPA" />
                            </div>
                            <div class="form-group required">
                                <label>Course</label>
                                <multiselect v-model="form.course_id" placeholder="Search Course" label="course"
                                    track-by="id" :options="option_course" :multiple="false" :close-on-select="true"
                                    :clear-on-select="false"></multiselect>
                                <has-error :form="form" field="course_id.id" />
                            </div>
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
                name: '',
                scholarship: '',
                GPA: '',
                course_id: '',
            }),
            option_course: [],
        }
    },
    methods: {
        create() {
            this.form.post('/api/create/student').then(() => {
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
