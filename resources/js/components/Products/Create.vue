<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Add Item</div>

                    <div class="card-body">
                        <alert-error :form="form"></alert-error>
                        <form method="POST">
                            <input type="hidden" required>
                            <input type="hidden" v-model="form.userID" class="form-control"
                                autocomplete="positionchrome-off">

                            <div class="form-group">
                                <label>Item Barcode</label>
                                <input v-model="form.item_barcode" type="text" class="form-control">
                                <has-error :form="form" field="Product Name" />
                            </div>

                            <div class="form-group">
                                <label>Item Name</label>
                                <input v-model="form.item_name" type="text" class="form-control">
                                <has-error :form="form" field="Price" />
                            </div>

                            <div class="form-group required">
                                <label>Courier</label>
                                <multiselect v-model="form.idCourier" placeholder="Search Courier"
                                   track-by="id" :options="option_measurement"
                                    :close-on-select="true" :clear-on-select="false" :custom-label="customLabel"></multiselect>
                                <has-error :form="form" field="idCourier" />
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
                item_barcode: '',
                item_name: '',
                idCourier: null,
            }),
            option_measurement: [],
        }
    },
    methods: {
        customLabel(option) {
            return `${option.name} ${option.lastname}`;
        },

        create() {
            const formData = new FormData();
            formData.append('Item_Barcode', this.form.item_barcode);
            formData.append('Item_Name', this.form.item_name);
            formData.append('idCourier', this.form.idCourier.id);

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
            }).catch((error) => {

                if (error.response && error.response.status === 409) {
                    toast.fire({
                        icon: 'error',
                        text: 'Item Barcode already exists!',
                    });
                } else {
                    toast.fire({
                        icon: 'error',
                        text: 'Something went wrong!',
                    });
                }
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
