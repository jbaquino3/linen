<template>
    <div>
        <table-filters
            :filterable="filterable"
            :actions="[
                {text: 'New Issuance', color: 'primary', emit: 'add', icon: mdiPlus}
            ]"
            v-model="filters"
            @search="s => search=s"
            @reload="reload"
            @add="$router.push('/auth/issuances/items')"
        ></table-filters>

        <v-alert v-if="transaction_error" type="error" text class="mt-2">
            {{transactions_error}}
        </v-alert>
        
        <v-card class="mt-2" flat>
            <v-data-table :headers="headers" :items="computed_transactions" :search="search" :disabled="transaction_loading" :loading="transaction_loading">
                <template v-slot:[`item.transaction_date`]="{ item }">
                    <div :class="($vuetify.theme.dark ? 'yellow--text' : ' font-weight-medium') + ' subtitle-1'">
                        {{item.transaction_date}}
                        <span v-if="!item.is_final" class="overline red--text">DRAFT</span>
                    </div>
                </template>

                <template v-slot:[`item.type`]="{ item }">
                    <div v-if="item.type=='ISSUANCE'" class="green--text font-weight-bold font-italic">ISSUANCE</div>
                    <div v-if="item.type=='CONDEMN'" class="orange--text font-weight-bold font-italic">CONDEMN</div>
                    <div v-if="item.type=='LOST'" class="red--text font-weight-bold font-italic">LOST</div>
                    <div v-if="item.type=='RETURN'" class="blue--text font-weight-bold font-italic">RETURN</div>
                </template>
                
                <template v-slot:[`item.location_name`]="{ item }">
                    <div :class="($vuetify.theme.dark ? 'yellow--text' : ' font-weight-medium') + ' subtitle-1 font-italic'">
                        {{item.location_name}}
                    </div>
                </template>
                
                <template v-slot:[`item.created_by_name`]="{ item }">
                    <div :class="($vuetify.theme.dark ? 'yellow--text' : ' font-weight-medium') + ' subtitle-1 font-italic'">
                        {{item.created_by_name}}
                    </div>
                </template>

                <template v-slot:[`item.actions`]="{ item }">
                    <div class="d-flex">
                        <div class="mr-1">
                            <table-list-button @click="openEdit(item)"></table-list-button>
                        </div>
                        <div class="mr-1">
                            <table-delete-button :disabled="item.is_final > 0" @delete="destroy(item)"></table-delete-button>
                        </div>
                    </div>
                </template>
            </v-data-table>
        </v-card>
    </div>
</template>

<script>
    import { onMounted, ref } from 'vue'
    import { useTransactionStore } from '@/stores/transaction'
    import { storeToRefs } from 'pinia'
    import { useRouter } from '@/plugins/UseRouter'
    import { mdiPlus } from '@mdi/js'

    export default {
        setup() {
            const transactionStore = useTransactionStore()
            const router = useRouter()
            const {
                computed_transactions,
                transaction_loading,
                transaction_error,
                selected_transaction,
                filters,
                filterable
            } = storeToRefs(transactionStore)
            const search = ref("")

            onMounted(() => {
                reload()
            })

            function reload() {
                transactionStore.fetchTransactions()
            }

            function openEdit(item) {
                selected_transaction.value = Object.assign({}, item)
                router.push("/auth/issuances/items")
            }

            function destroy(item) {
                transactionStore.deleteTransaction(item.id)
            }

            return {
                computed_transactions,
                transaction_loading,
                transaction_error,
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

    const icons = {
        mdiPlus
    }

    const headers = [
        {text: "Date", value: "transaction_date"},
        {text: "Type", value: "type"},
        {text: "Ward/Office", value: "location_name"},
        {text: "Created By", value: "created_by_name"},
        {text: "Actions", value: "actions"},
    ]
</script>