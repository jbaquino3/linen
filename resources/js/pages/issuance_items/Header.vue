<template>
    <div class="tg-wrap">
        <table class="tg mb-3" cellspacing="0" cellpadding="0">
            <colgroup>
                <col style="width: 120px">
                <col>
                <col style="width: 130px">
                <col style="width: 150px">
            </colgroup>
            <thead>
                <tr>
                    <td class="tg-3xi5" rowspan="4">
                        <img src="/assets/logo.png" width="100" height="100" class="my-2">
                    </td>
                    <td class="tg-3y8d" colspan="3">
                        <span>Republic of the Philippines</span>
                        <br>
                        <span>Department of Health</span>
                        <br>
                        <span class="font-weight-bold">BAGUIO GENERAL HOSPITAL AND MEDICAL CENTER</span>
                        <br>
                        <span>Baguio City</span>
                    </td>
                </tr>
                <tr>
                    <td class="tg-p7cy" rowspan="3">
                        <div style="font-weight:bold" class="caption">GENERAL SERVICES SECTION - LINEN ROOM</div>
                        <div style="font-weight:bold" class="subtitle-1" v-if="selected_transaction.type == 'ISSUANCE'">INVENTORY (LINEN) CUSTODIAN SLIP</div>
                        <div style="font-weight:bold" class="subtitle-1" v-else>LIST OF LINEN FOR CONDEMN</div>
                    </td>
                    <td class="tg-ycr8">Form No.</td>
                    <td class="tg-ycr8 text-center" v-if="selected_transaction.type == 'ISSUANCE'">HS-GSS-008</td>
                    <td class="tg-ycr8 text-center" v-else>HS-GSS-012</td>
                </tr>
                <tr>
                    <td class="tg-ycr8">Revision No.</td>
                    <td class="tg-ycr8 text-center" v-if="selected_transaction.type == 'ISSUANCE'">1</td>
                    <td class="tg-ycr8 text-center" v-else>Φ</td>
                </tr>
                <tr>
                    <td class="tg-ycr8">Effectivity Date</td>
                    <td class="tg-ycr8 text-center" v-if="selected_transaction.type == 'ISSUANCE'">August 16, 2019</td>
                    <td class="tg-ycr8 text-center" v-else>August 1, 2014</td>
                </tr>
            </thead>
        </table>
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
        }
    }
</script>

<style scoped>
    .tg  {border-collapse:collapse;border-color:#000;border-spacing:0;margin:0px auto;}
    .tg td{background-color:#E0FFEB;border-color:#000;border-style:solid;border-width:1px;color:#594F4F;
    font-family:Arial, sans-serif;font-size:9pt;overflow:hidden;padding:2px 5px;word-break:normal;}
    .tg th{background-color:#9DE0AD;border-color:#000;border-style:solid;border-width:1px;color:#493F3F;
    font-family:Arial, sans-serif;font-size:9pt;font-weight:normal;overflow:hidden;padding:2px 5px;word-break:normal;}
    .tg .tg-ycr8{background-color:#ffffff;font-size:9.5pt;color:#000;text-align:left;vertical-align:top}
    .tg .tg-3y8d{background-color:#ffffff;color:#000000;font-size:9pt;text-align:center;vertical-align:middle}
    .tg .tg-3xi5{background-color:#ffffff;border-color:inherit;text-align:center;vertical-align:middle}
    .tg .tg-p7cy{background-color:#ffffff;padding:2px 5px;color:#000000;text-align:center;vertical-align:middle;}
    table  {border-collapse:collapse;border-color:#000;border-spacing:0;margin:0px;width:100%}
</style>