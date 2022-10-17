<template>
    <div>
        <v-dialog v-model="product_dialog" persistent max-width="400">
            <v-card :loading="dialog_loading" :disabled="dialog_loading">
                <v-card-title>
                    <v-container class="d-flex" fluid>
                        {{selected_product ? "Update Product" : "Create Product"}}
                        <v-spacer></v-spacer>
                        <v-icon @click="closeDialog">{{mdiClose}}</v-icon>
                    </v-container>
                </v-card-title>

                <v-card-text>
                    <v-container fluid>
                        <v-alert v-if="dialog_error" type="error" text class="mb-2">
                            {{dialog_error}}
                        </v-alert>

                        <v-row>
                            <v-col cols="12">
                                <v-select
                                    label="Type"
                                    v-model="product.type"
                                    :items="['WARD', 'OFFICE']"
                                ></v-select>
                            </v-col>

                            <v-col cols="12">
                                <v-text-field
                                    label="Name"
                                    v-model="product.name"
                                    @input="product.name = product.name.toUpperCase()"
                                ></v-text-field>
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
    import { storeToRefs } from 'pinia'
    import { mdiClose } from '@mdi/js'
    import { ref, watch, onMounted } from 'vue'

    export default {
        setup() {
            const productStore = useProductStore()
            const { product_dialog, selected_product, dialog_loading, dialog_error } = storeToRefs(productStore)
            const { storage_select_items } = storeToRefs(useStorageStore())
            const product = ref({})
            const date_menu = ref(false)

            function closeDialog() {
                product_dialog.value = false
                selected_product.value = null
            }

            function save() {
                if(selected_product.value) {
                    productStore.updateProduct(product.value, product.value.id)
                } else {
                    productStore.createProduct(product.value)
                }
            }

            function assignProduct() {
                product.value = selected_product.value ? selected_product.value : {}
            }

            onMounted(() => {
                assignProduct()
            })

            watch(selected_product, (currentValue) => {
                assignProduct()
            })

            return {
                product_dialog,
                product,
                dialog_loading,
                dialog_error,
                selected_product,
                date_menu,
                storage_select_items,
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