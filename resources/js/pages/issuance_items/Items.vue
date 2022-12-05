<template>
    <div ref="printable" class="mt-2">
        <v-card class="pa-0" light :width="selected_transaction.type=='ISSUANCE' ? 1000 : 800" tile flat>
            <v-card-text class="black--text">
                <Header></Header>

                <issuance-list v-if="selected_transaction.type=='ISSUANCE'"></issuance-list>
                <condemn-list v-else></condemn-list>
            </v-card-text>
        </v-card>
    </div>
</template>

<script>
    import { useTransactionStore } from '@/stores/transaction'
    import { storeToRefs } from 'pinia'

    export default {
        setup() {
            const { selected_transaction } = storeToRefs(useTransactionStore())

            return {
                selected_transaction
            }
        },

        components: {
            Header: () => import("./Header"),
            IssuanceList: () => import("./IssuanceList"),
            CondemnList: () => import("./CondemnList"),
        }
    }
</script>