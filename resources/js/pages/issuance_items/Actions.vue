<template>
    <v-card flat class="no-print">
        <v-card-actions>
            <v-select
                label="Ward/Unit"
                class="shrink mr-2"
                outlined
                dense
                hide-details
                :items="location_select_items"
                v-model="selected_transaction.location_id"
                :disabled="!!selected_transaction.id"
            ></v-select>

            <v-spacer></v-spacer>

            <div v-if="selected_transaction.location_id">
                <v-btn color="primary" @click="print" v-if="selected_transaction.is_final">
                    <v-icon left>{{mdiPrinter}}</v-icon>
                    Print
                </v-btn>

                <v-btn color="error" @click="finalize" v-else>
                    Save as Final
                </v-btn>
            </div>
        </v-card-actions>
    </v-card>
</template>

<script>
    import { useLocationStore } from '@/stores/location'
    import { useTransactionStore } from '@/stores/transaction'
    import { storeToRefs } from 'pinia'
    import { mdiPrinter } from '@mdi/js'
    import useAlerts from '@/utils/alerts'
    import usePrinter from '@/plugins/UsePrinter'

    export default {
        setup() {
            const transactionStore = useTransactionStore()
            const { location_select_items } = storeToRefs(useLocationStore())
            const { selected_transaction } = storeToRefs(transactionStore)

            function print() {
                usePrinter().print(false)
            }

            function finalize() {
                if(selected_transaction.value.is_final) {
                    usePrinter().print(false)
                } else {
                    useAlerts().alertConfirmation({
                        title: "Issuance Completed?",
                        text: "This will mark this issuance final and uneditable. Would you like to continue?"
                    }).then(async (swal) => {
                        if(swal.isConfirmed) {
                            useAlerts().toggleLoading(true)
                            const res = await transactionStore.finalizeTransaction()
                            useAlerts().toggleLoading(false)
                        }
                    })
                }
            }

            return {
                location_select_items,
                selected_transaction,
                ...icons,
                print,
                finalize
            }
        },
    }

    const icons = {
        mdiPrinter
    }
</script>