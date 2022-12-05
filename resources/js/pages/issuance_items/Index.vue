<template>
    <div>
        <actions></actions>

        <v-alert v-if="transaction_error" type="error" text class="mt-2">
            {{transaction_error}}
        </v-alert>

        <div>
            <div v-if="selected_transaction.type == 'ISSUANCE'">
                <issuance-form></issuance-form>
            </div>
            <div ref="printable">
                <items></items>
            </div>
        </div>
    </div>
</template>

<script>
    import { useTransactionStore } from '@/stores/transaction'
    import { storeToRefs } from 'pinia'
    import { useRoute } from '@/plugins/UseRouter'
    import { onMounted } from 'vue'

    export default {
        setup() {
            const { transaction_error, selected_transaction } = storeToRefs(useTransactionStore())
            const route = useRoute()

            onMounted(() => {
                selected_transaction.value.type = route.query.type
            })

            return {
                transaction_error,
                selected_transaction
            }
        },

        components: {
            IssuanceForm: () => import('./IssuanceForm'),
            ReturnForm: () => import('./ReturnForm'),
            Actions: () => import('./Actions'),
            Items: () => import('./Items')
        }
    }
</script>