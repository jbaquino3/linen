<template>
    <div>
        <actions></actions>

        <v-alert v-if="transaction_error" type="error" text class="mt-2">
            {{transaction_error}}
        </v-alert>

        <div class="d-flex">
            <div>
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

    export default {
        setup() {
            const { transaction_error } = storeToRefs(useTransactionStore())

            return {
                transaction_error
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