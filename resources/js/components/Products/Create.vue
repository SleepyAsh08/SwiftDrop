<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Add Product</div>

                    <div class="card-body">
                        <alert-error :form="form"></alert-error>
                        <form method="POST">
                            <input type="hidden" required>
                            <input type="hidden" v-model="form.userID" class="form-control"
                                autocomplete="positionchrome-off">

                            <div class="form-group">
                                <label>Product Name</label>
                                <input v-model="form.product_name" type="text" class="form-control">
                                <has-error :form="form" field="Product Name" />
                            </div>

                            <div class="form-group required">
                                <label>Category</label>
                                <multiselect v-model="form.category_id" placeholder="Search Category" label="category"
                                    track-by="id" :options="option_category" :close-on-select="true"
                                    :clear-on-select="false"></multiselect>
                                <has-error :form="form" field="category_id" />
                            </div>
                            <div class="form-group">
                                <label>Price</label>
                                <input v-model="form.price" type="text" class="form-control">
                                <has-error :form="form" field="Price" />
                            </div>
                            <div class="form-group">
                                <label>Quantity</label>
                                <input v-model="form.quantity" type="number" class="form-control">
                                <has-error :form="form" field="Quantity" />
                            </div>

                            <div class="form-group required">
                                <label>Measurement</label>
                                <multiselect v-model="form.measurement_id" placeholder="Search Measurement"
                                    label="measurement" track-by="id" :options="option_measurement"
                                    :close-on-select="true" :clear-on-select="false"></multiselect>
                                <has-error :form="form" field="measurement_id" />
                            </div>


                            <div class="form-group">
                                <label>Upload Photos</label>
                                <input type="file" @change="onFileChange" multiple class="form-control">
                                <br>
                                <input type="file" @change="onFileChange1" multiple class="form-control">
                                <br>
                                <input type="file" @change="onFileChange2" multiple class="form-control">
                            </div>


                            <div class="form-group">
                                <label>Description</label>
                                <textarea v-model="form.description" type="text" class="form-control"></textarea>
                                <has-error :form="form" field="Description" />
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

import axios from 'axios';
import Multiselect from 'vue-multiselect';
import 'vue-multiselect/dist/vue-multiselect.min.css';

export default {
    components: {
        Multiselect
    },
    data() {
        return {
            form: new Form({
                userID: 0,
                product_name: '',
                measurement_id: null,
                price: 0,
                quantity: 0,
                description: '',
            }),
            option_measurement: [],
            option_category: [],
            photos: [],
            photos1: [],
            photos2: [],

        }
    },
    methods: {
        onFileChange(e) {
            this.photos = Array.from(e.target.files);
        },
        onFileChange1(e) {
            this.photos1 = Array.from(e.target.files);
        },
        onFileChange2(e) {
            this.photos2 = Array.from(e.target.files);

        },
        create() {
            console.log('Photos before posting:', this.photos);
            const formData = new FormData();
            formData.append('product_name', this.form.product_name);
            // formData.append('measurement_id', this.form.measurement_id.id);
            formData.append('price', this.form.price);
            formData.append('quantity', this.form.quantity);
            formData.append('description', this.form.description);


            // Append each selected photo file to the formData
            this.photos.forEach((photo, index) => {
                formData.append(`photos[${index}]`, photo);
            });

            // Append each selected photo file to the formData
            this.photos1.forEach((photo, index) => {
                formData.append(`photos1[${index}]`, photo);
            });

            // Append each selected photo file to the formData
            this.photos2.forEach((photo, index) => {
                formData.append(`photos2[${index}]`, photo);
            });

            axios.post('/api/product/create', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then(() => {
                toast.fire({
                    icon: 'success',
                    text: 'Data Saved.',
                })
                this.form.reset();
                this.photos = []; // Reset the photos array
            }).catch(() => {
                toast.fire({
                    icon: 'error',
                    text: 'Something went wrong!',
                })
            });
        },
        loadMeasurement() {
            axios.get('/api/measurement/all')
                .then(response => {
                    this.option_measurement = response.data.data;
                    console.log('Loaded measurements:', this.option_measurement);
                });
        },
        loadCategory() {
            axios.get('/api/category/all')
                .then(response => {
                    this.option_category = response.data.data;
                    console.log('Loaded category:', this.option_category);
                });
        },
    },
    watch: {
        row: function () {
            this.form.fill(this.row);
            console.log('Form data filled from row:', this.form);
        }
    },
    mounted() {
        this.loadMeasurement();
        this.loadCategory();
    }
}
</script>
