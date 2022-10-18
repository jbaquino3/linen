<template>
    <div>
        <table-filters
            :filterable="filterable"
            :actions="[
                {text: 'Add Product', color: 'primary', emit: 'add', icon: mdiPlus}
            ]"
            v-model="filters"
            @search="s => search=s"
            @reload="reload"
            @add="product_dialog=true"
        ></table-filters>

        <v-alert v-if="products_error" type="error" text class="mt-2">
            {{products_error}}
        </v-alert>
        
        <v-card class="mt-2" flat>
            <v-data-table :headers="headers" :items="computed_products" :search="search" :disabled="products_loading" :loading="products_loading">
                <template v-slot:[`item.name`]="{ item }">
                    <div class="d-flex flex-column">
                        <div :class="($vuetify.theme.dark ? 'yellow--text' : ' ')">
                            <v-tooltip dark right v-if="item.name.length > 40">
                                <template v-slot:activator="{ on, attrs }">
                                    <span v-bind="attrs" v-on="on" class="title">
                                        {{item.name.slice(0,18)}}...{{item.name.slice(-14)}}
                                    </span>
                                </template>
                                <span>{{item.name}}</span>
                            </v-tooltip>
                            <div v-else class="title">
                                {{item.name}}
                            </div>
                        </div>
                        <div :class="($vuetify.theme.dark ? 'grey--text text--lighten-5' : '') + ' mb-2'">
                            <v-chip dark x-small label color="green">
                                ₱{{item.unit_cost}}/{{item.unit.toLowerCase()}}
                            </v-chip>
                            <v-chip dark x-small label class="ml-2" color="orange">
                                <v-icon left x-small>{{mdiDresser}}</v-icon>
                                {{item.storage.storage_name}}
                            </v-chip>
                        </div>
                    </div>
                </template>

                <template v-slot:[`item.material`]="{ item }">
                    <table-material-details
                        :quantity="item.material_quantity"
                        :unit="item.material.unit"
                        :description="item.material.description"
                        :stock-number="item.material.stock_number"
                        :type="item.material.type"
                        :unit-cost="item.material.unit_cost"
                    ></table-material-details>
                </template>

                <template v-slot:[`item.available`]="{ item }">
                    <available-bar
                        :disabled="!!item.archived_at"
                        disabled_label="ARCHIVED"
                        :available="item.quantity-item.quantity_issued"
                        :total="item.quantity"
                        :unit="item.unit"
                    ></available-bar>
                </template>

                <template v-slot:[`item.actions`]="{ item }">
                    <div class="d-flex">
                        <div class="mr-1">
                            <table-edit-button @click="openEdit(item)"></table-edit-button>
                        </div>
                        <div class="mr-1">
                            <table-delete-button :disabled="item.quantity_issued>0" @delete="destroy(item)"></table-delete-button>
                        </div>
                    </div>
                </template>
            </v-data-table>
        </v-card>
    </div>
</template>

<script>
    import { onMounted, ref } from 'vue'
    import { useProductStore } from '@/stores/product'
    import { storeToRefs } from 'pinia'
    import { mdiDresser, mdiPlus } from '@mdi/js'

    export default {
        setup() {
            const productStore = useProductStore()
            const {
                computed_products,
                products_loading,
                product_dialog,
                products_error,
                selected_product,
                filters,
                filterable
            } = storeToRefs(productStore)
            const search = ref("")

            onMounted(() => {
                reload()
            })

            function reload() {
                productStore.fetchProducts()
            }

            function openEdit(item) {
                selected_product.value = Object.assign({}, item)
                product_dialog.value = true
            }

            function destroy(item) {
                productStore.deleteProduct(item.bulk_id)
            }

            return {
                computed_products,
                products_loading,
                products_error,
                product_dialog,
                headers,
                search,
                filters,
                filterable,
                openEdit,
                destroy,
                reload,
                ...icons
            }
        },
    }

    const headers = [
        {text: "Product", value: "name"},
        {text: "Material Used", value: "material"},
        {text: "Available", value: "available"},
        {text: "Actions", value: "actions"},
    ]

    const icons = {
        mdiDresser, mdiPlus
    }
</script>