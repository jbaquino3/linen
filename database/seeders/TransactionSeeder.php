<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Location;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Transaction::truncate();
        TransactionItem::truncate();
        
        $this->seedWardIssuances();
        $this->seedOfficeIssuances();
        $this->seedWardReturns();
        $this->seedOfficeReturns();
    }

    private function seedWardIssuances() {
        $daily_wards = \DB::select("
            select
                cast(issued_date as date) as issuance_date,
                issued_ward_id
            from nora.paul.linen_products
            where issued_ward_id is not null
            and deleted_at is null
            group by cast(issued_date as date), issued_ward_id
            order by issuance_date, issued_ward_id
        ");

        foreach($daily_wards as $daily_ward) {
            $ward = \DB::table("nora.paul.linen_ward")->where("id", $daily_ward->issued_ward_id)->first();
            $transaction = Transaction::create([
                "location_id" => Location::where("type", "WARD")->where("name", $ward->ward_name)->first()->id,
                "type" => "ISSUANCE",
                "is_final" => true
            ]);

            $products = \DB::select("
                select
                    distinct
                    product_bulk_id,
                    issuance_additional_cost
                from nora.paul.linen_products
                where cast(issued_date as date) = ?
                and deleted_at is null
                and issued_ward_id = ?
            ", [$daily_ward->issuance_date, $daily_ward->issued_ward_id]);

            foreach($products as $product) {
                $stock_numbers = \DB::table("nora.paul.linen_products")
                    ->where("product_bulk_id", $product->product_bulk_id)
                    ->whereNull("deleted_at")
                    ->whereRaw("cast(issued_date as date) = '" . $daily_ward->issuance_date . "'")
                    ->where("issued_ward_id", $daily_ward->issued_ward_id)
                    ->pluck("product_stock_id");

                for($i=0; $i<sizeof($stock_numbers); $i++) {
                    $stock_numbers[$i] = intval(explode("-",$stock_numbers[$i])[1]);
                }

                TransactionItem::create([
                    "transaction_id" => $transaction->id,
                    "product_bulk_id" => $product->product_bulk_id,
                    "stock_numbers" => $stock_numbers,
                    "issuance_additional_cost" => $product->issuance_additional_cost
                ]);
            }
        }
    }

    private function seedOfficeIssuances() {
        $daily_offices = \DB::select("
            select
                cast(issued_date as date) as issuance_date,
                issued_office_id
            from nora.paul.linen_products
            where issued_office_id is not null
            and deleted_at is null
            group by cast(issued_date as date), issued_office_id
            order by issuance_date, issued_office_id
        ");

        foreach($daily_offices as $daily_office) {
            $office = \DB::table("nora.paul.linen_office")->where("id", $daily_office->issued_office_id)->first();
            $transaction = Transaction::create([
                "location_id" => Location::where("type", "office")->where("name", $office->office_name)->first()->id,
                "type" => "ISSUANCE",
                "is_final" => true
            ]);

            $products = \DB::select("
                select
                    distinct
                    product_bulk_id,
                    issuance_additional_cost
                from nora.paul.linen_products
                where cast(issued_date as date) = ?
                and deleted_at is null
                and issued_office_id = ?
            ", [$daily_office->issuance_date, $daily_office->issued_office_id]);

            foreach($products as $product) {
                $stock_numbers = \DB::table("nora.paul.linen_products")
                    ->where("product_bulk_id", $product->product_bulk_id)
                    ->whereNull("deleted_at")
                    ->whereRaw("cast(issued_date as date) = '" . $daily_office->issuance_date . "'")
                    ->where("issued_office_id", $daily_office->issued_office_id)
                    ->pluck("product_stock_id");

                for($i=0; $i<sizeof($stock_numbers); $i++) {
                    $stock_numbers[$i] = intval(explode("-",$stock_numbers[$i])[1]);
                }

                TransactionItem::create([
                    "transaction_id" => $transaction->id,
                    "product_bulk_id" => $product->product_bulk_id,
                    "stock_numbers" => $stock_numbers,
                    "issuance_additional_cost" => $product->issuance_additional_cost
                ]);
            }
        }
    }

    private function seedWardReturns() {
        $daily_wards = \DB::select("
            select
                cast(returned_date as date) as returned_date,
                issued_ward_id
            from nora.paul.linen_products
            where issued_ward_id is not null
            and deleted_at is null
            group by cast(returned_date as date), issued_ward_id
            order by returned_date, issued_ward_id
        ");

        foreach($daily_wards as $daily_ward) {
            $ward = \DB::table("nora.paul.linen_ward")->where("id", $daily_ward->issued_ward_id)->first();
            $transaction = Transaction::create([
                "location_id" => Location::where("type", "WARD")->where("name", $ward->ward_name)->first()->id,
                "type" => "RETURN",
                "is_final" => true
            ]);

            $products = \DB::select("
                select
                    distinct
                    product_bulk_id
                from nora.paul.linen_products
                where cast(returned_date as date) = ?
                and deleted_at is null
                and issued_ward_id = ?
            ", [$daily_ward->returned_date, $daily_ward->issued_ward_id]);

            foreach($products as $product) {
                $stock_numbers = \DB::table("nora.paul.linen_products")
                    ->where("product_bulk_id", $product->product_bulk_id)
                    ->whereNull("deleted_at")
                    ->whereRaw("cast(returned_date as date) = '" . $daily_ward->returned_date . "'")
                    ->where("issued_ward_id", $daily_ward->issued_ward_id)
                    ->pluck("product_stock_id");

                for($i=0; $i<sizeof($stock_numbers); $i++) {
                    $stock_numbers[$i] = intval(explode("-",$stock_numbers[$i])[1]);
                }

                TransactionItem::create([
                    "transaction_id" => $transaction->id,
                    "product_bulk_id" => $product->product_bulk_id,
                    "stock_numbers" => $stock_numbers
                ]);
            }
        }
    }

    private function seedOfficeReturns() {
        $daily_offices = \DB::select("
            select
                cast(returned_date as date) as returned_date,
                issued_office_id
            from nora.paul.linen_products
            where issued_office_id is not null
            and deleted_at is null
            group by cast(returned_date as date), issued_office_id
            order by returned_date, issued_office_id
        ");

        foreach($daily_offices as $daily_office) {
            $office = \DB::table("nora.paul.linen_office")->where("id", $daily_office->issued_office_id)->first();
            $transaction = Transaction::create([
                "location_id" => Location::where("type", "office")->where("name", $office->office_name)->first()->id,
                "type" => "RETURN",
                "is_final" => true
            ]);

            $products = \DB::select("
                select
                    distinct
                    product_bulk_id
                from nora.paul.linen_products
                where cast(returned_date as date) = ?
                and deleted_at is null
                and issued_office_id = ?
            ", [$daily_office->returned_date, $daily_office->issued_office_id]);

            foreach($products as $product) {
                $stock_numbers = \DB::table("nora.paul.linen_products")
                    ->where("product_bulk_id", $product->product_bulk_id)
                    ->whereNull("deleted_at")
                    ->whereRaw("cast(returned_date as date) = '" . $daily_office->returned_date . "'")
                    ->where("issued_office_id", $daily_office->issued_office_id)
                    ->pluck("product_stock_id");

                for($i=0; $i<sizeof($stock_numbers); $i++) {
                    $stock_numbers[$i] = intval(explode("-",$stock_numbers[$i])[1]);
                }

                TransactionItem::create([
                    "transaction_id" => $transaction->id,
                    "product_bulk_id" => $product->product_bulk_id,
                    "stock_numbers" => $stock_numbers
                ]);
            }
        }
    }
}
