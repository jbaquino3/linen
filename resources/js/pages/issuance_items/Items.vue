<template>
    <div ref="printable" class="mt-2">
        <v-card class="pa-0" light width="1000" tile flat>
            <v-card-text class="black--text">
                <Header></Header>

                <div class="d-flex">
                    <div>Ward/Office: {{selected_transaction.location_name}}</div>
                    <v-spacer></v-spacer>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th class="text-center font-weight-bold subtitle-2">QUANTITY</th>
                            <th class="text-center font-weight-bold subtitle-2">UNIT</th>
                            <th class="text-center font-weight-bold subtitle-2">ITEM DESCRIPTION</th>
                            <th class="text-center font-weight-bold subtitle-2">UNIT AMOUNT</th>
                            <th class="text-center font-weight-bold subtitle-2">TOTAL AMOUNT</th>
                            <th class="text-center font-weight-bold subtitle-2">DATE ISSUED</th>
                            <th class="text-center font-weight-bold subtitle-2">REMARKS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in selected_transaction.items" :key="index">
                            <td class="text-center">{{item.quantity}}</td>
                            <td class="text-center">{{getProduct(item.product_bulk_id).unit}}</td>
                            <td class="text-center">{{getProduct(item.product_bulk_id).name}}</td>
                            <td class="text-center">{{formatCurrency((100 + item.issuance_additional_cost) * getProduct(item.product_bulk_id).unit_cost / 100)}}</td>
                            <td class="text-center">{{formatCurrency((((100 + item.issuance_additional_cost) * getProduct(item.product_bulk_id).unit_cost / 100)).toFixed(2) * item.quantity)}}</td>
                            <td class="text-center">{{new Date(selected_transaction.transaction_date).toString().substring(0,15)}}</td>
                            <td class="text-center">Added {{item.issuance_additional_cost}}% to unit amount</td>
                        </tr>
                    </tbody>
                </table>

                <div class="mt-15 pt-15 body-1 no-break">
                    <v-row>
                        <v-col cols="4">
                            <div class="black--text">Received By:</div>

                            <v-divider class="black mt-15"></v-divider>
                            <div class="caption text-center mb-7">Signature Over Printed Name</div>

                            <v-divider class="black mt-5"></v-divider>
                            <div class="caption text-center mb-6">Position</div>

                            <v-divider class="black mt-5"></v-divider>
                            <div class="caption text-center">Date</div>
                        </v-col>
                        <v-col cols="3"></v-col>
                        <v-col cols="4">
                            <div class="black--text">Received From:</div>

                            <v-divider class="black mt-15"></v-divider>
                            <div class="caption text-center mb-7">Signature Over Printed Name</div>

                            <v-divider class="black mt-5"></v-divider>
                            <div class="caption text-center mb-6">Position</div>

                            <v-divider class="black mt-5"></v-divider>
                            <div class="caption text-center">Date</div>
                        </v-col>
                        <v-col cols="1"></v-col>
                    </v-row>
                    
                </div>
            </v-card-text>
        </v-card>
    </div>
</template>

<script>
    import { useTransactionStore } from '@/stores/transaction'
    import { useLocationStore } from '@/stores/location'
    import { useProductStore } from '@/stores/product'
    import { storeToRefs } from 'pinia'
    import { computed } from 'vue'

    export default {
        setup() {
            const { selected_transaction } = storeToRefs(useTransactionStore())
            const { computed_locations } = storeToRefs(useLocationStore())
            const { data } = storeToRefs(useProductStore())

            const location_name = computed(() => {
                if(selected_transaction.value.location_id) {
                    const location = computed_locations.value.find(loc => loc.id == selected_transaction.value.location_id)

                    return location ? location.name : null
                }

                return null
            })

            const location_type = computed(() => {
                if(selected_transaction.value.location_id) {
                    const location = computed_locations.value.find(loc => loc.id == selected_transaction.value.location_id)

                    return location ? location.type : null
                }

                return null
            })

            function getProduct(bulk_id) {
                const product = data.value.find(d => d.bulk_id == bulk_id)

                return product ? product : {}
            }

            function formatCurrency(amount) {
                const formatter = new Intl.NumberFormat('en-US', {
                    style: 'currency',
                    currency: 'PHP',
                    minimumFractionDigits: 2, // (this suffices for whole numbers, but will print 2500.10 as $2,500.1)
                    maximumFractionDigits: 2, // (causes 2500.99 to be printed as $2,501)
                })

                return formatter.format(amount)
            }

            return {
                selected_transaction,
                location_name,
                location_type,
                getProduct,
                formatCurrency
            }
        },

        components: {
            Header: () => import("./Header")
        }
    }
</script>

<style scoped>
    table th + th { border-left:1px solid #000; }
    table td + td { border-left:1px solid #000; }
    table  {border-collapse:collapse;border-color:#000;border-spacing:0;margin:0px;width:100%}
    table td{border-color:#000;border-style:solid;border-width:1px;
    font-family:Arial, sans-serif;font-size:12px;overflow:hidden;padding:5px 3px !important;word-break:normal;}
    table th{border-color:#000;border-style:solid;border-width:1px;
    font-family:Arial, sans-serif;font-size:12px;font-weight:normal;overflow:hidden;padding:2px 3px !important;word-break:normal;}
</style>