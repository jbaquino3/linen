<template>
    <v-dialog v-model="return_dialog" width="600px">
        <v-card flat :loading="transaction_loading" :disabled="transaction_loading">
            <v-card-title>
                Return/Condemn Products
            </v-card-title>
            <v-card-text>
                <v-alert v-if="transaction_error" type="error" text class="mb-2">
                    {{transaction_error}}
                </v-alert>
                
                <v-form ref="form">
                    <v-select
                        label="Transaction Type"
                        :items="['RETURN', 'CONDEMN', 'LOST']"
                        :rules="[v => !!v || 'Transaction type is required']"
                        v-model="item.type"
                    ></v-select>

                    <v-select
                        label="Ward/Unit"
                        :rules="[v => !!v || 'Ward/Unit is required']"
                        :items="location_select_items"
                        v-model="item.location_id"
                    ></v-select>

                    <v-select
                        label="Select Product"
                        :items="product_select_items"
                        :hint="selected_product ? 'Material: ' + selected_product.material_name : null"
                        persistent-hint
                        :value="item.product_bulk_id"
                        :rules="[v => !!v || 'Product is required']"
                        item-text="product_name"
                        item-value="bulk_id"
                        @input="setProduct"
                    ></v-select>

                    <v-text-field
                        v-if="selected_product"
                        :disabled="!item.product_bulk_id"
                        label="Quantity"
                        type="number"
                        :rules="[v => (!!v && Number(v) <= Number(selected_product.quantity)) || 'There are only ' + selected_product.quantity + ' ' + selected_product.unit.toLowerCase() + '/s issued.']"
                        :hint="selected_product ? 'Issued: ' + selected_product.quantity + ' ' + selected_product.unit.toLowerCase() + '/s @ ₱' + selected_product.unit_cost + ' each' : null"
                        persistent-hint
                        :value="item.quantity"
                        @input="setQuantity"
                    >
                        <template v-slot:append>
                            {{selected_product ? selected_product.unit.toLowerCase() + '/s' : null}}
                        </template>
                    </v-text-field>

                    <v-text-field
                        v-if="selected_product"
                        :disabled="!item.product_bulk_id"
                        label="Stock #s"
                        v-model="stock_numbers"
                        :hint="'Issued: ' + stock_numbers_available"
                        persistent-hint
                        @input="setStockNumbers"
                        :rules="[v => (!!v && item.stock_numbers.every(val => selected_product.stock_numbers.includes(val))) || 'Available stock #s: ' + stock_numbers_available]"
                    ></v-text-field>
                </v-form>

                <v-btn dark color="primary" @click="save">
                    Submit
                </v-btn>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>

<script>
    import { useLocationStore } from '@/stores/location'
    import { useTransactionStore } from '@/stores/transaction'
    import { storeToRefs } from 'pinia'
    import { reactive, onMounted, ref, computed } from 'vue'
    import { useHelpers } from '@/utils/helpers'

    export default {
        setup() {
            const transactionStore = useTransactionStore()
            const { issued_products, transaction_loading, transaction_error, return_dialog } = storeToRefs(transactionStore)
            const { location_select_items } = storeToRefs(useLocationStore())
            const form = ref(null)
            const helpers = useHelpers()
            const stock_numbers = ref("")
            const item = reactive({
                type: "RETURN",
                location_id: null,
                product_bulk_id: null,
                quantity: 0,
                stock_numbers: []
            })

            const product_select_items = computed(() => {
                return issued_products.value.filter(p => p.location_id == item.location_id)
            })

            const selected_product = computed(() => {
                return item.product_bulk_id ? product_select_items.value.find(g => g.bulk_id == item.product_bulk_id) : null
            })

            const stock_numbers_available = computed(() => selected_product.value ? helpers.integerArrayToRanges(selected_product.value.stock_numbers) : "")

            onMounted(() => {
                transactionStore.fetchIssuedProducts()
            })

            function setQuantity(v) {
                if(v.length == 0) {
                    v = '0'
                }

                item.quantity = parseInt(v)
                item.stock_numbers = selected_product.value.stock_numbers.slice(0, parseInt(v))
                stock_numbers.value = helpers.integerArrayToRanges(item.stock_numbers)
            }

            function setStockNumbers(v) {
                if(/\d+(?:-\d+)?(?:,\d+(?:-\d+)?)*/.test(v)) {
                    item.stock_numbers = helpers.rangesToIntegerArray(v)
                    item.quantity = item.stock_numbers.length
                }
            }

            function setProduct(bulk_id) {
                const curr_product =  product_select_items.value.find(g => g.bulk_id == bulk_id)

                item.product_bulk_id = bulk_id
                item.quantity = curr_product.quantity
                item.stock_numbers = curr_product.stock_numbers
                stock_numbers.value = helpers.integerArrayToRanges(curr_product.stock_numbers)
            }

            async function save() {
                if(form.value.validate()) {
                    const res = await transactionStore.createTransaction(item)
                    if(!transaction_error.value) {
                        item.location_id = null
                        item.product_bulk_id = null
                        item.quantity = 0
                        item.stock_numbers = []

                        return_dialog.value = false
                    }
                }
            }

            return {
                form,
                product_select_items,
                location_select_items,
                stock_numbers_available,
                item,
                return_dialog,
                stock_numbers,
                selected_product,
                transaction_loading,
                transaction_error,
                setProduct,
                setStockNumbers,
                setQuantity,
                save
            }
        },
    }
</script>