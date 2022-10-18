<template>
    <div>
        <v-dialog v-model="product_dialog" persistent max-width="600">
            <v-card :loading="dialog_loading" :disabled="dialog_loading">
                <v-card-title>
                    <v-container class="d-flex" fluid>
                        {{selected_product ? "Update Product" : "Create Product"}}
                        <v-spacer></v-spacer>
                        <v-icon @click="closeDialog">{{mdiClose}}</v-icon>
                    </v-container>
                </v-card-title>

                <v-card-text v-if="product">
                    <v-container fluid>
                        <v-alert v-if="dialog_error" type="error" text class="mb-2">
                            {{dialog_error}}
                        </v-alert>

                        <v-row>
                            <v-col cols="12">
                                <v-textarea
                                    label="Product Description"
                                    v-model="product.name"
                                    rows="2"
                                ></v-textarea>
                            </v-col>
                            
                            <v-col cols="12">
                                <v-select
                                    label="Material Used"
                                    v-model="product.material_stock_number"
                                    :items="material_select_items"
                                ></v-select>
                            </v-col>

                            <v-col cols="12">
                                <v-text-field
                                    v-if="product.material_unit"
                                    :label="'Quantity of used material. Available: ' + material_available + ' ' + product.material_unit.toLowerCase() + '/s'"
                                    v-model="product.material_quantity"
                                    type="number"
                                >
                                    <template v-slot:append>
                                        {{product.material_unit.toLowerCase()}}/s @ ₱{{product.material_unit_cost}} each
                                    </template>
                                </v-text-field>
                            </v-col>

                            <v-col cols="12">
                                <v-select
                                    label="Storage"
                                    v-model="product.storage_id"
                                    :items="storage_select_items"
                                ></v-select>
                            </v-col>

                            <v-col cols="6">
                                <v-text-field
                                    label="Product Quantity"
                                    v-model="product.quantity"
                                    type="number"
                                ></v-text-field>
                            </v-col>

                            <v-col cols="6">
                                <v-select
                                    label="Product Unit"
                                    v-model="product.unit"
                                    :items="['PIECE', 'SPOOL', 'YARD', 'ROLL', 'SACK/BAG']"
                                ></v-select>
                            </v-col>

                            <v-col cols="12">
                                <v-text-field
                                    label="Stock Numbers"
                                    v-model="stock_numbers"
                                    readonly
                                ></v-text-field>
                            </v-col>

                            <v-col cols="6">
                                <v-text-field
                                    label="Product Unit Cost"
                                    v-model="product.unit_cost"
                                    type="number"
                                ></v-text-field>
                            </v-col>

                            <v-col cols="6">
                                <v-menu v-model="date_menu" :close-on-content-click="false" min-width="auto">
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-text-field v-model="product.create_date" label="Create Date" readonly v-bind="attrs" v-on="on"></v-text-field>
                                    </template>
                                    <v-date-picker v-model="product.create_date" no-title @input="date_menu = false"></v-date-picker>
                                </v-menu>
                            </v-col>
                        </v-row>

                        <v-btn dark color="primary" @click="save">
                            Save
                        </v-btn>
                    </v-container>
                </v-card-text>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
    import { useProductStore } from '@/stores/product'
    import { useStorageStore } from '@/stores/storage'
    import { useMaterialStore } from '@/stores/material'
    import { useHelpers } from '@/utils/helpers'
    import { storeToRefs } from 'pinia'
    import { mdiClose } from '@mdi/js'
    import { ref, watch, onMounted, computed } from 'vue'

    export default {
        setup() {
            const helper = useHelpers()
            const productStore = useProductStore()
            const { product_dialog, selected_product, dialog_loading, dialog_error } = storeToRefs(productStore)
            const { storage_select_items } = storeToRefs(useStorageStore())
            const { material_select_items } = storeToRefs(useMaterialStore())
            const product = ref({})
            const date_menu = ref(false)

            const material_available = computed(() => {
                const material = material_select_items.value.find(i => i.value == product.value.material_stock_number)

                return material ? parseFloat(material.available) - parseFloat(product.value.material_quantity.length > 0 ? product.value.material_quantity : 0) : ''
            })

            const material_stock_number = computed(() => {
                return product.value.material_stock_number
            })

            const quantity = computed(() => {
                return product.value.quantity
            })

            const stock_numbers = ref("")

            function closeDialog() {
                product_dialog.value = false
                selected_product.value = null
            }

            function save() {
                if(selected_product.value) {
                    productStore.updateProduct(product.value, product.value.bulk_id)
                } else {
                    productStore.createProduct(product.value)
                }
            }

            function assignProduct() {
                product.value = Object.assign({
                    create_date: new Date().toISOString().substring(0,10),
                    stock_numbers: [],
                    quantity: "",
                    material_quantity: 0
                }, selected_product.value)
                stock_numbers.value = helper.integerArrayToRanges(product.value.stock_numbers)
                product.value.starting_stock_number = product.value.stock_numbers.length > 0 ? product.value.stock_numbers[0] : 1
            }

            onMounted(() => {
                assignProduct()
            })

            watch(selected_product, () => {
                assignProduct()
            })

            watch(material_stock_number, () => {
                product.value.material_quantity = product.value.material_quantity ? product.value.material_quantity : material_available.value
            })

            watch(quantity, (val) => {
                if(val.length > 0) {
                    const start = product.value.starting_stock_number
                    const end = start + parseInt(val) - 1
                    product.value.stock_numbers = helper.rangesToIntegerArray(start + "-" + end)
                    stock_numbers.value = helper.integerArrayToRanges(product.value.stock_numbers)
                }
            })

            return {
                product_dialog,
                product,
                dialog_loading,
                dialog_error,
                selected_product,
                date_menu,
                storage_select_items,
                material_select_items,
                material_available,
                stock_numbers,
                closeDialog,
                save,
                ...icons
            }
        },
    }

    const icons = {
        mdiClose
    }
</script>