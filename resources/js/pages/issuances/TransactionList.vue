<template>
    <div>
        <table-filters
            :filterable="filterable"
            :actions="[
                {text: 'New Transaction', color: 'primary', emit: 'add', icon: mdiPlus}
            ]"
            v-model="filters"
            @search="s => search=s"
            @reload="reload"
            @add="new_dialog=true"
        ></table-filters>

        <v-dialog v-model="new_dialog" width="375">
            <v-card>
                <v-list>
                    <v-list-item @click="openIssuance">
                        <v-list-item-title>
                            Issuance
                        </v-list-item-title>
                    </v-list-item>
                    <v-list-item @click="openReturn">
                        <v-list-item-title>
                            Return/Condemn
                        </v-list-item-title>
                    </v-list-item>
                </v-list>
            </v-card>
        </v-dialog>

        <return-dialog></return-dialog>

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
                            <table-list-button @click="openEdit(item)" v-if="item.type=='ISSUANCE'"></table-list-button>
                            <v-tooltip top v-else>
                                <template v-slot:activator="{ on, attrs }">
                                    <v-btn v-bind="attrs" v-on="on" small elevation="0" color="red" dark @click="openEdit(item)">
                                        <v-icon>
                                            {{mdiPrinter}}
                                        </v-icon>
                                    </v-btn>
                                </template>
                                <span>Print</span>
                            </v-tooltip>
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
    import { mdiPlus, mdiPrinter } from '@mdi/js'
    import ReturnDialog from './ReturnDialog.vue'

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
                filterable,
                return_dialog
            } = storeToRefs(transactionStore)
            const search = ref("")
            const new_dialog = ref(false)

            onMounted(() => {
                reload()
            })

            function reload() {
                transactionStore.fetchTransactions()
            }

            function openEdit(item) {
                selected_transaction.value = Object.assign({}, item)
                router.push("/auth/issuances/items?type=" + item.type)
            }

            function destroy(item) {
                transactionStore.deleteTransaction(item.id)
            }

            function openIssuance() {
                new_dialog.value = false
                router.push('/auth/issuances/items')
            }

            function openReturn () {
                new_dialog.value = false
                return_dialog.value = true
            }

            return {
                computed_transactions,
                transaction_loading,
                transaction_error,
                headers,
                new_dialog,
                search,
                return_dialog,
                filters,
                filterable,
                openEdit,
                destroy,
                reload,
                openIssuance,
                openReturn,
                ...icons
            }
        },

        components: {
            ReturnDialog
        }
    }

    const icons = {
        mdiPlus, mdiPrinter
    }

    const headers = [
        {text: "Date", value: "transaction_date"},
        {text: "Type", value: "type"},
        {text: "Ward/Unit", value: "location_name"},
        {text: "Created By", value: "created_by_name"},
        {text: "Actions", value: "actions"},
    ]
</script>