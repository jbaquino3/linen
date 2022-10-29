<template>
    <div class="flex-grow-1 flex-shrink-0 mr-2 mt-2">
        <v-card class="no-print" flat :loading="transaction_loading" :disabled="transaction_loading">
            <v-card-text>
                <v-form ref="form" lazy-validation>
                    <v-select
                        label="Select Product"
                        :items="product_select_items"
                        :hint="selected_product ? 'Material: ' + selected_product.material_name : null"
                        persistent-hint
                        :value="transaction_item.product_bulk_id"
                        :loading="products_loading"
                        :disabled="products_loading"
                        :rules="[v => !!v || 'Product is required']"
                        @input="setProduct"
                    ></v-select>

                    <v-text-field
                        v-if="selected_product"
                        :disabled="!transaction_item.product_bulk_id"
                        label="Issue Quantity"
                        type="number"
                        :rules="[v => (!!v && Number(v) <= Number(selected_product.available)) || 'There are only ' + selected_product.available + ' ' + selected_product.unit.toLowerCase() + '/s available.']"
                        :hint="selected_product ? 'Available: ' + selected_product.available + ' ' + selected_product.unit.toLowerCase() + '/s @ ₱' + selected_product.unit_cost + ' each' : null"
                        persistent-hint
                        :value="transaction_item.quantity"
                        @input="setQuantity"
                    >
                        <template v-slot:append>
                            {{selected_product ? selected_product.unit.toLowerCase() + '/s' : null}}
                        </template>
                    </v-text-field>

                    <v-text-field
                        v-if="selected_product"
                        :disabled="!transaction_item.product_bulk_id"
                        label="Issue Stock #s"
                        v-model="stock_numbers"
                        :hint="'Available: ' + stock_numbers_available"
                        persistent-hint
                        @input="setStockNumbers"
                        :rules="[v => (!!v && transaction_item.stock_numbers.every(val => selected_product.stock_numbers_available.includes(val))) || 'Available stock #s: ' + stock_numbers_available]"
                    ></v-text-field>

                    <v-text-field
                        v-if="selected_product"
                        :disabled="!transaction_item.product_bulk_id"
                        label="Additional Cost"
                        type="number"
                        v-model="transaction_item.issuance_additional_cost"
                        :rules="[v => !!v || 'This field is required.']"
                    >
                        <template v-slot:append>
                            %
                        </template>
                    </v-text-field>

                    <v-btn dark color="primary" @click="save">
                        Add To transaction
                    </v-btn>
                </v-form>
            </v-card-text>
        </v-card>
    </div>
</template>

<script>
    import { useProductStore } from '@/stores/product'
    import { useTransactionStore } from '@/stores/transaction'
    import { storeToRefs } from 'pinia'
    import { computed, ref } from 'vue'
    import { useHelpers } from '@/utils/helpers'

    export default {
        setup() {
            const transactionStore = useTransactionStore()
            const { product_select_items, products_loading } = storeToRefs(useProductStore())
            const { transaction_loading } = storeToRefs(transactionStore)
            const transaction_item = ref({
                stock_numbers: [],
                quantity: 0,
                issuance_additional_cost: 10,
                stock_numbers: [] 
            })
            const helpers = useHelpers()
            const stock_numbers = ref("")
            const form = ref(null)

            const selected_product = computed(() => {
                return product_select_items.value.find(g => g.value == transaction_item.value.product_bulk_id)
            })

            const stock_numbers_available = computed(() => selected_product.value ? helpers.integerArrayToRanges(selected_product.value.stock_numbers_available) : "")

            function setQuantity(v) {
                if(v.length == 0) {
                    v = '0'
                }

                transaction_item.value.quantity = parseInt(v)
                transaction_item.value.stock_numbers = selected_product.value.stock_numbers_available.slice(0, parseInt(v))
                stock_numbers.value = helpers.integerArrayToRanges(transaction_item.value.stock_numbers)
            }

            function setStockNumbers(v) {
                if(/\d+(?:-\d+)?(?:,\d+(?:-\d+)?)*/.test(v)) {
                    transaction_item.value.stock_numbers = helpers.rangesToIntegerArray(v)
                    transaction_item.value.quantity = transaction_item.value.stock_numbers.length
                }
            }

            function setProduct(bulk_id) {
                const curr_product =  product_select_items.value.find(g => g.value == bulk_id)

                transaction_item.value.product_bulk_id = bulk_id
                transaction_item.value.quantity = curr_product.available
                transaction_item.value.stock_numbers = curr_product.stock_numbers_available
                stock_numbers.value = helpers.integerArrayToRanges(curr_product.stock_numbers_available)
            }

            async function save() {
                if(form.value.validate()) {
                    const res = await transactionStore.addTransactionItem(transaction_item.value)
                    if(res) {
                        transaction_item.value = {
                            stock_numbers: [],
                            quantity: 0,
                            issuance_additional_cost: 10,
                            stock_numbers: [] 
                        }
                        form.value.resetValidation()
                    }
                }
            }

            return {
                form,
                product_select_items,
                products_loading,
                stock_numbers_available,
                transaction_loading,
                transaction_item,
                stock_numbers,
                selected_product,
                setProduct,
                setStockNumbers,
                setQuantity,
                save
            }
        },
    }
</script>