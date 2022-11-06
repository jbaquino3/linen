<template>
    <div>
        <v-card flat class="no-print">
            <v-card-actions>
                <v-btn color="primary" @click="print" :dark="!!selected_report" :disabled="!selected_report">
                    <v-icon left>{{mdiPrinter}}</v-icon>
                    Print
                </v-btn>
            </v-card-actions>
        </v-card>
        
        <div ref="printable" class="mt-2">
            <v-card class="pa-0" light tile flat>
                <v-card-text class="black--text">
                    <Header></Header>

                    <table v-if="selected_report">
                        <thead>
                            <tr>
                                <th class="text-left" colspan="6">UNIT/WARD: {{selected_report.location_name}}</th>
                                <th class="text-left" colspan="3">Date: {{selected_report.month}} {{selected_report.year}}</th>
                            </tr>
                            <tr>
                                <th rowspan="2" class="font-weight-bold subtitle-2">DESCRIPTION</th>
                                <th colspan="8" class="font-weight-bold subtitle-2">QUANTITY</th>
                            </tr>
                            <tr>
                                <th class="text-center font-weight-bold caption">Unit Price</th>
                                <th class="text-center font-weight-bold caption">Beg. Bal</th>
                                <th class="text-center font-weight-bold caption">Date Issued</th>
                                <th class="text-center font-weight-bold caption">TOTAL</th>
                                <th class="text-center font-weight-bold caption">Date Condemned</th>
                                <th class="text-center font-weight-bold caption">Date Returned</th>
                                <th class="text-center font-weight-bold caption">Ending Balance</th>
                                <th class="text-center font-weight-bold caption">Losses</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item) in selected_report.items" :key="item.id">
                                <td class="text-left">{{item.name}}</td>
                                <td class="text-center">₱{{item.unit_cost}}</td>
                                <td class="text-center">{{item.beg_balance}}</td>
                                <td class="text-center">{{item.issued_date}}</td>
                                <td class="text-center">{{item.total_issued}}</td>
                                <td class="text-center">{{item.condemned_date}} <span v-if="item.condemned_date">({{item.condemned_quantity}})</span></td>
                                <td class="text-center">{{item.returned_date}} <span v-if="item.returned_date">({{item.returned_quantity}})</span></td>
                                <td class="text-center">{{item.ending_balance}}</td>
                                <td class="text-center">{{item.lost_date}} <span v-if="item.lost_date">({{item.lost_quantity}})</span></td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="mt-15 pt-15 body-1 no-break">
                        <v-row>
                            <v-col cols="4">
                                <div class="black--text">Submitted By:</div>

                                <v-divider class="black mt-15"></v-divider>
                                <div class="caption text-center mb-7">Signature Over Printed Name</div>
                            </v-col>
                            <v-col cols="3"></v-col>
                            <v-col cols="4">
                                <div class="black--text">Received By:</div>

                                <v-divider class="black mt-15"></v-divider>
                                <div class="caption text-center mb-7">Signature Over Printed Name</div>
                            </v-col>
                            <v-col cols="1"></v-col>
                        </v-row>
                        
                    </div>
                </v-card-text>
            </v-card>
        </div>
    </div>
</template>

<script>
    import Header from "./Header"
    import usePrinter from '@/plugins/UsePrinter'
    import { useReportStore } from '@/stores/report'
    import { storeToRefs } from 'pinia'
    import { useRouter } from '@/plugins/UseRouter'
    import { watchEffect } from 'vue'
    import { mdiPrinter } from '@mdi/js'

    export default {
        setup() {
            const { selected_report } = storeToRefs(useReportStore())
            const router = useRouter()

            watchEffect(() => { 
                if(!selected_report.value) {
                    router.push("/auth/reports")
                }
            })

            function print() {
                usePrinter().print()
            }

            return {
                selected_report,
                print,
                ...icons
            }
        },

        components: {
            Header
        }
    }

    const icons = {
        mdiPrinter
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