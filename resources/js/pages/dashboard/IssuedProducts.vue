<template>
    <div>
        <v-alert v-if="issued_error" type="error" text>
            {{issued_error}}
        </v-alert>

        <div class="d-flex mt-2">
            <div class="d-flex flex-column">
                <div class="title font-weight-bold text-decoration-underline" @click="$router.push('/auth/issuances')">TRANSACTIONS</div>
                <div class="caption font-italic">Issuances, Condemns, Returns, Losses</div>
            </div>

            <v-spacer></v-spacer>

            <v-text-field
                outlined
                hide-details
                dense
                v-model="search"
                label="Search Transaction"
                :append-icon="mdiMagnify"
                clearable
            ></v-text-field>
        </div>

        <v-data-table class="mt-2" :headers="headers" :items="issued_products" :loading="issued_loading" :search="search">
            <template v-slot:[`item.product_name`]="{ item }">
                <div class="d-flex flex-column">
                    <div :class="($vuetify.theme.dark ? 'yellow--text' : ' ') + ' subtitle-1 font-italic'" @click="search=item.product_name">
                        {{item.product_name}}
                    </div>
                    <div class="caption grey--text">
                        Material Stock Number: {{item.material_stock_number}}
                    </div>
                </div>
            </template>

            <template v-slot:[`item.location_name`]="{ item }">
                <div :class="($vuetify.theme.dark ? 'white--text' : ' ') + ' d-flex flex-column'">
                    <div class="caption grey--text"  @click="search=item.location_type">
                        <v-icon x-small color="grey">{{mdiOfficeBuilding}}</v-icon>
                        {{item.location_type}}
                    </div>
                    <div @click="search=item.location_name">{{item.location_name}}</div>
                </div>
            </template>

            <template v-slot:[`item.type`]="{ item }">
                <div class="d-flex flex-column">
                    <div :class="($vuetify.theme.dark ? 'white--text' : ' ')">
                        <div class="subtitle-1 font-weight-bold font-italic" @click="search=item.type">
                            {{item.type}}
                        </div>
                    </div>
                    <div :class="($vuetify.theme.dark ? 'grey--text text--lighten-5' : '') + ' mb-2'">
                        {{item.quantity}} {{item.unit.toLowerCase()}}/s @ ₱{{item.unit_cost}}
                        <span class="red--text">+{{item.issuance_additional_cost}}%</span>
                    </div>
                    <div :class="($vuetify.theme.dark ? 'grey--text text--lighten-5' : '') + ' mb-2'">
                        <v-icon x-small>{{mdiCalendar}}</v-icon>
                        {{item.transaction_date}}
                        <v-icon x-small class="ml-2">{{mdiAccount}}</v-icon>
                        {{item.created_by_name ? item.created_by_name : 'Unknown Staff'}}
                    </div>
                </div>
            </template>
        </v-data-table>
    </div>
</template>

<script>
    import { useTransactionStore } from '@/stores/transaction'
    import { useAuthStore } from '@/stores/auth'
    import { storeToRefs } from 'pinia'
    import { onMounted, ref, computed } from 'vue'
    import { mdiCalendar, mdiAccount, mdiOfficeBuilding, mdiMagnify } from '@mdi/js'

    const transactionStore = useTransactionStore()
    const authStore = useAuthStore()

    export default {
        setup() {
            const { issued_products, issued_loading, issued_error } = storeToRefs(transactionStore)
            const { user } = storeToRefs(authStore)
            const search = ref("")

            onMounted(() => transactionStore.fetchIssuedProducts())
            const headers = computed(() => {
                if(user.value.role == "USER") {
                    return [
                        {text: "Product", value: "product_name"},
                        {text: "Transaction", value: "type"},
                        {text: "", value: "transaction_date", align: " d-none"},
                        {text: "", value: "location_type", align: " d-none"},
                        {text: "", value: "created_by_name", align: " d-none"},
                    ]
                } else {
                    return [
                        {text: "Product", value: "product_name"},
                        {text: "Area/Unit", value: "location_name"},
                        {text: "Transaction", value: "type"},
                        {text: "", value: "transaction_date", align: " d-none"},
                        {text: "", value: "location_type", align: " d-none"},
                        {text: "", value: "created_by_name", align: " d-none"},
                    ]
                }
            })

            return {
                issued_products,
                issued_loading,
                issued_error,
                headers,
                search,
                ...icons
            }
        },
    }

    const icons = {
        mdiCalendar, mdiAccount, mdiOfficeBuilding, mdiMagnify
    }
</script>