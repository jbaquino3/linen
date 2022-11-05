<template>
    <div>
        <v-btn @click="print">Print</v-btn>
        <div ref="printable" class="mt-2">
            <v-card class="pa-0" light tile flat>
                <v-card-text class="black--text">
                    <Header></Header>

                    <table>
                        <thead>
                            <tr>
                                <th class="text-left" colspan="3">UNIT/WARD:</th>
                                <th class="text-left" colspan="6">Date:</th>
                            </tr>
                            <tr>
                                <th rowspan="2" class="font-weight-bold subtitle-2">QUANTITY</th>
                                <th colspan="8" class="font-weight-bold subtitle-2">UNIT</th>
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
                            <tr>
                                <td class="text-center">Patient Gown blue with BGHMC Logo 11 by 12 in.</td>
                                <td class="text-center">P100.34</td>
                                <td class="text-center">10</td>
                                <td class="text-center">2022-11-04</td>
                                <td class="text-center">20</td>
                                <td class="text-center">2022-11-05 (5)</td>
                                <td class="text-center">2022-11-05 (5)</td>
                                <td class="text-center">10</td>
                                <td class="text-center">3</td>
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

    export default {
        setup() {
            function print() {
                usePrinter().print()
            }

            return {
                print
            }
        },

        components: {
            Header
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