<template>
    <div>
        <request-summary no-link></request-summary>

        <table-filters
            :filterable="filterable"
            :actions="[
                {text: 'Create Request', color: 'primary', emit: 'add', icon: mdiPlus}
            ]"
            v-model="filters"
            @search="s => search=s"
            @reload="reload"
            @add="request_dialog=true"
            class="mt-2"
        ></table-filters>

        <v-alert v-if="requests_error" type="error" text class="mt-2">
            {{requests_error}}
        </v-alert>
        
        <v-card class="mt-2" flat>
            <v-data-table :headers="headers" :items="requests_loading ? [] : computed_requests" :search="search" :disabled="requests_loading" :loading="requests_loading">
                <template v-slot:[`item.location_name`]="{ item }">
                    <div :class="($vuetify.theme.dark ? 'yellow--text' : ' black--text') + ' subtitle-1'">
                        {{item.location_name}} ({{item.location_type}})
                    </div>
                </template>

                <template v-slot:[`item.name`]="{ item }">
                    <div class="d-flex flex-column">
                        <div :class="($vuetify.theme.dark ? 'white--text' : ' black--text') + ' subtitle-1 font-italic'">
                            {{item.quantity}} {{item.unit.toLowerCase()}}/s {{item.name}}
                        </div>
                        <div :class="($vuetify.theme.dark ? 'white--text' : ' grey--text') + ' caption font-italic'">
                            <v-icon x-small>{{mdiAccount}}</v-icon>
                            {{item.requested_by_name}}
                        </div>
                        <div :class="($vuetify.theme.dark ? 'white--text' : ' grey--text') + ' caption font-italic'">
                            <v-icon x-small>{{mdiCalendar}}</v-icon>
                            {{item.requested_at}}
                        </div>
                    </div>
                </template>

                <template v-slot:[`item.status`]="{ item }">
                    <div class="d-flex flex-column">
                        <div class="title font-weight-bold">
                            <div v-if="item.cancelled_at" class="red--text">{{item.status}}</div>
                            <div v-else-if="item.issued_at" class="green--text">{{item.status}}</div>
                            <div v-else-if="item.prepared_at" class="blue--text">{{item.status}}</div>
                            <div v-else-if="item.processed_at" class="yellow--text text--darken-2">{{item.status}}</div>
                            <div v-else class="orange--text">{{item.status}}</div>
                        </div>
                        <div :class="($vuetify.theme.dark ? 'white--text' : ' grey--text') + ' caption font-italic'" v-if="item.processed_at || item.cancelled_at">
                            <v-icon x-small>{{mdiAccount}}</v-icon>
                            <span v-if="item.cancelled_at">{{item.cancelled_by_name}}</span>
                            <span v-else-if="item.issued_at">{{item.issued_by_name}}</span>
                            <span v-else-if="item.prepared_at">{{item.prepared_by_name}}</span>
                            <span v-else-if="item.processed_at">{{item.processed_by_name}}</span>
                        </div>
                        <div :class="($vuetify.theme.dark ? 'white--text' : ' grey--text') + ' caption font-italic'" v-if="item.processed_at || item.cancelled_at">
                            <v-icon x-small>{{mdiCalendar}}</v-icon>
                            <span v-if="item.cancelled_at">{{item.cancelled_at}}</span>
                            <span v-else-if="item.issued_at">{{item.issued_at}}</span>
                            <span v-else-if="item.prepared_at">{{item.prepared_at}}</span>
                            <span v-else-if="item.processed_at">{{item.processed_at}}</span>
                        </div>
                    </div>
                </template>

                <template v-slot:[`item.remarks`]="{ item }">
                    <div class="d-flex flex-column">
                        <div v-for="(remark, index) in item.remarks" :key="index">
                            <div :class="($vuetify.theme.dark ? 'white--text' : ' black--text') + ' subtitle-1 d-flex flex-column'">
                                {{remark.remarks}}
                                <div :class="($vuetify.theme.dark ? 'white--text' : ' grey--text') + ' caption font-italic'">
                                    <v-icon x-small v-if="remark.remarks_by_name">{{mdiAccount}}</v-icon>
                                    <span>{{remark.remarks_by_name}}</span>
                                    <div>
                                        <v-icon x-small>{{mdiCalendar}}</v-icon>
                                        {{remark.created_at.substring(0,)}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <v-btn text color="primary" x-small width="75" @click="openRemark(item)" v-if="authStore.user.role != 'USER'">Add Remark</v-btn>
                    </div>
                </template>

                <template v-slot:[`item.actions`]="{ item }">
                    <div class="d-flex">
                        <div class="mr-1" v-if="authStore.user.role != 'USER'">
                            <v-btn v-if="item.status=='PENDING'" small dark color="yellow darken-2" @click="process(item)">Process</v-btn>
                            <v-btn v-if="item.status=='PROCESSING'" small dark color="blue" @click="ready(item)">Ready</v-btn>
                            <v-btn v-if="item.status=='READY FOR PICKUP'" small dark color="green" @click="issue(item)">Issue</v-btn>
                            <v-btn v-if="item.status=='ISSUED'" small :dark="!!item.transaction" color="purple" :disabled="!item.transaction" @click="openPrint(item)">Print</v-btn>
                        </div>
                        <div class="mr-1">
                            <table-delete-button :disabled="!!item.processed_at" @delete="destroy(item)"></table-delete-button>
                        </div>
                    </div>
                </template>
            </v-data-table>
        </v-card>
    </div>
</template>

<script>
    import { onMounted, ref } from 'vue'
    import { useRequestStore } from '@/stores/request'
    import { useAuthStore } from '@/stores/auth'
    import { useTransactionStore } from '@/stores/transaction'
    import { storeToRefs } from 'pinia'
    import { useRouter } from '@/plugins/UseRouter'
    import { mdiPlus, mdiAccount, mdiCalendar } from '@mdi/js'
    import RequestSummary from './RequestSummary'

    export default {
        setup() {
            const authStore = useAuthStore()
            const requestStore = useRequestStore()
            const { selected_transaction, data } = storeToRefs(useTransactionStore())
            const {
                computed_requests,
                requests_loading,
                request_dialog,
                remark_dialog,
                requests_error,
                selected_request,
                filters,
                filterable,
                headers,
            } = storeToRefs(requestStore)
            const search = ref("")
            const router = useRouter()

            onMounted(() => {
                reload()
            })

            function reload() {
                requestStore.fetchRequests()
            }

            function openRemark(item) {
                selected_request.value = Object.assign({}, item)
                remark_dialog.value = true
            }

            function destroy(item) {
                requestStore.deleteRequest(item.id)
            }

            function process(item) {
                requestStore.processRequest(item.id)
            }

            function openPrint(item) {
                selected_transaction.value = item.transaction
                router.push("/auth/issuances/items")
            }

            function issue(item) {
                selected_transaction.value.location_id = item.location_id
                selected_transaction.value.location_name = item.location_name
                selected_transaction.value.items = []
                router.push("/auth/issuances/items?type=ISSUANCE")
            }

            function ready(item) {
                requestStore.readyRequest(item.id)
            }

            return {
                computed_requests,
                requests_loading,
                requests_error,
                request_dialog,
                headers,
                search,
                filters,
                filterable,
                authStore,
                openRemark,
                destroy,
                reload,
                process,
                ready,
                issue,
                openPrint,
                ...icons
            }
        },

        components: {
            RequestSummary
        }
    }

    const icons = {
        mdiPlus, mdiAccount, mdiCalendar
    }
</script>