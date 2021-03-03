<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createProductModal">Create new product</button>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-md-12">
                <ul class="list-group">
                    <li class="list-group-item d-flex align-items-center justify-content-between" v-for="product in products" :key="product.id">
                        <span>{{ product.name }}: {{ product.quantity }}</span>

                        <!-- Product action buttons -->
                        <div class="actions">
                            <a href="javascript:void(0)" class="btn btn-outline-primary" @click="triggerEditModal(product.id)">Edit</a>
                            <a href="javascript:void(0)" class="btn btn-outline-danger" @click="deleteProduct(product.id)">Remove</a>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="col-md-12">
                page: {{ pagination.currentPage }} of {{ pagination.totalPages }}
                <a href="javascript:void(0);" @click="fetchProducts(--pagination.currentPage)" v-if="pagination.currentPage > 1">Prev</a>
                <a href="javascript:void(0);" @click="fetchProducts(++pagination.currentPage)" v-if="pagination.currentPage < pagination.totalPages">Next</a>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="createProductModal" tabindex="-1" role="dialog" aria-labelledby="createProductModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createProductModalLabel">Create Product</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="createProduct">
                            <div class="form-group">
                                <label for="createFormProductName">Name:</label>
                                <input type="text" v-model="createForm.name" class="form-control" id="createFormProductName"/>
                            </div>

                            <div class="form-group">
                                <label for="createFormProductQuantity">Quantity:</label>
                                <input type="number" v-model="createForm.quantity" class="form-control" id="createFormProductQuantity">
                            </div>

                            <div class="form-group">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editProductModal" tabindex="-1" role="dialog" aria-labelledby="editProductModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="updateProduct">
                            <div class="form-group">
                                <label for="editFormProductName">Name:</label>
                                <input type="text" v-model="editForm.name" class="form-control" id="editFormProductName"/>
                            </div>

                            <div class="form-group">
                                <label for="editFormProductQuantity">Quantity:</label>
                                <input type="number" v-model="editForm.quantity" class="form-control" id="editFormProductQuantity">
                            </div>

                            <div class="form-group">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {http} from '../http-module'

export default {
    name: "Index",
    data() {
        return {
            currentPage: 1,
            products: [],
            pagination: {},

            createForm: {
                name: '',
                quantity: '',
            },
            editForm: {
                id: null,
                name: '',
                quantity: '',
            },
        }
    },
    mounted() {
        this.fetchProducts();
    },
    methods: {
        fetchProducts(page = 1) {
            http
                .get(`/api/products?page=${page}`)
                .then((response) => {
                    this.products = response.data.data;
                    this.pagination = response.data.pagination
                })
        },
        createProduct() {
            http
                .post(`/api/products`, this.createForm)
                .then(() => {
                    $('#createProductModal').modal('hide');

                    this.$notify({
                        title: 'New product created!',
                        text: 'Reload page to see it.',
                        group: 'notifications',
                        duration: 2500,
                        type: 'success',
                    })
                })
        },
        updateProduct() {
            http
                .put(`/api/products/${this.editForm.id}`, this.editForm)
                .then((response) => {
                    $('#editProductModal').modal('hide');
                    this.editForm = {};

                    this.$notify({
                        title: 'You updated a product!',
                        text: 'Please update a page to see changes!',
                        group: 'notifications',
                        duration: 2500,
                        type: 'success',
                    })
                })
        },
        deleteProduct(id) {
            http
                .delete(`/api/products/${id}`)
                .then((response) => {
                    this.$notify({
                        title: 'You removed a product!',
                        text: 'Please update a page to see changes!',
                        group: 'notifications',
                        duration: 2500,
                        type: 'success',
                    })
                })
        },
        triggerEditModal(id) {
            http.get(`/api/products/${id}`).then(({ data }) => {
                this.editForm.id = id;
                this.editForm.name = data.data.name;
                this.editForm.quantity = data.data.quantity;
            })

            $('#editProductModal').modal('show');
        },
    }
}
</script>

<style scoped>

</style>