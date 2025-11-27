<?php

namespace Database\Seeders;

use App\Models\AuthProd;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AuthProdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        AuthProd::create([
            "name" => 'Fruita i verdura',
            "slug" => 'fruita-i-verdura',
            "sector_id" => 3,
            "created_at" => Carbon::now()->toDateTimeString(),
            "updated_at" => Carbon::now()->toDateTimeString()
        ])->setTranslations('name', ['es' => 'Fruta y Verdura', 'en' => 'Fruit and Vegetables'])
        ->setTranslations('slug', ['es' => 'fruta-y-verdura', 'en' => 'fruit-and-vegetables'])
        ->save();

        AuthProd::create([
            "name" => 'Roba',
            "slug" => 'roba',
            "sector_id" => 4,
            "created_at" => Carbon::now()->toDateTimeString(),
            "updated_at" => Carbon::now()->toDateTimeString()
        ])->setTranslations('name', ['es' => 'Ropa', 'en' => 'Clothing'])
        ->setTranslations('slug', ['es' => 'ropa', 'en' => 'clothing'])
        ->save();

        AuthProd::create([
            "name" => 'Pell/marroquineria',
            "slug" => 'pell-marroquineria',
            "sector_id" => 5,
            "created_at" => Carbon::now()->toDateTimeString(),
            "updated_at" => Carbon::now()->toDateTimeString()
        ])->setTranslations('name', ['es' => 'Pieles/Marroquineria', 'en' => 'Pieles/Marroquineria'])
        ->setTranslations('slug', ['es' => 'pieles-marroquineria', 'en' => 'pieles-marroquineria'])
        ->save();

        AuthProd::create([
            "name" => 'Calçat',
            "slug" => 'calcat',
            "sector_id" => 2,
            "created_at" => Carbon::now()->toDateTimeString(),
            "updated_at" => Carbon::now()->toDateTimeString()
        ])->setTranslations('name', ['es' => 'Calzado', 'en' => 'Footwear'])
        ->setTranslations('slug', ['es' => 'calzado', 'en' => 'footwear'])
        ->save();

        AuthProd::create([
            "name" => 'Joguines',
            "slug" => 'joguines',
            "sector_id" => 1,
            "created_at" => Carbon::now()->toDateTimeString(),
            "updated_at" => Carbon::now()->toDateTimeString()
        ])->setTranslations('name', ['es' => 'Juguetes', 'en' => 'Toys'])
        ->setTranslations('slug', ['es' => 'juguetes', 'en' => 'toys'])
        ->save();

        AuthProd::create([
            "name" => 'Bijuteria i perfums',
            "slug" => 'bijuteria-i-perfums',
            "sector_id" => 4,
            "created_at" => Carbon::now()->toDateTimeString(),
            "updated_at" => Carbon::now()->toDateTimeString()
        ])->setTranslations('name', ['es' => 'Bisuteria y Perfumes', 'en' => 'Jewellery and Perfumes'])
        ->setTranslations('slug', ['es' => 'bisuteria-y-perfumes', 'en' => 'jewellery-and-perfumes'])
        ->save();

        AuthProd::create([
            "name" => 'Restauració',
            "slug" => 'restauracio',
            "sector_id" => 1,
            "created_at" => Carbon::now()->toDateTimeString(),
            "updated_at" => Carbon::now()->toDateTimeString()
        ])->setTranslations('name', ['es' => 'Restauración', 'en' => 'Restauration'])
        ->setTranslations('slug', ['es' => 'restauracion', 'en' => 'restauration'])
        ->save();

        AuthProd::create([
            "name" => 'Pesca salada, encurtits i embotits',
            "slug" => 'pesca-salada-encurtits-i-embotits',
            "sector_id" => 3,
            "created_at" => Carbon::now()->toDateTimeString(),
            "updated_at" => Carbon::now()->toDateTimeString()
        ])->setTranslations('name', ['es' => 'Pesca salada, encurtidos y embutidos', 'en' => 'Salted fish, pickles and sausages'])
        ->setTranslations('slug', ['es' => 'pesca-salada-encurtidos-y-embutidos', 'en' => 'salted-fish-pickles-and-sausages'])
        ->save();

        AuthProd::create([
            "name" => 'Equipament tèxtil per la llar',
            "slug" => 'equipament-textil-per-la-llar',
            "sector_id" => 5,
            "created_at" => Carbon::now()->toDateTimeString(),
            "updated_at" => Carbon::now()->toDateTimeString()
        ])->setTranslations('name', ['es' => 'Equipamiento textil para la casa', 'en' => 'Household textile equipment'])
        ->setTranslations('slug', ['es' => 'equipamiento-textil-para-la-casa', 'en' => 'household-textile-equipment'])
        ->save();

        AuthProd::create([
            "name" => 'Ferreteria',
            "slug" => 'ferreteria',
            "sector_id" => 1,
            "created_at" => Carbon::now()->toDateTimeString(),
            "updated_at" => Carbon::now()->toDateTimeString()
        ])->setTranslations('name', ['es' => 'Ferreteria', 'en' => 'Ironmongery'])
        ->setTranslations('slug', ['es' => 'ferreteria', 'en' => 'ironmongery'])
        ->save();

        AuthProd::create([
            "name" => 'Sabates nens',
            "slug" => 'sabates-nens',
            "sector_id" => 2,
            "created_at" => Carbon::now()->toDateTimeString(),
            "updated_at" => Carbon::now()->toDateTimeString()
        ])->setTranslations('name', ['es' => 'Calzado niños', 'en' => 'Footwear Kids'])
        ->setTranslations('slug', ['es' => 'calzado-ninos', 'en' => 'footwear-kids'])
        ->save();

    }
}
