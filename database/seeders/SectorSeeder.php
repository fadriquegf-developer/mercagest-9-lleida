<?php

namespace Database\Seeders;

use App\Models\Sector;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SectorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Sector::create([
            "id" => 1,
            "name" => 'Altres Mercaderies',
            "slug" => 'altres-mercaderies',
        ])->setTranslations('name', ['es' => 'Otras Mercancias', 'en' => 'Other Merchandise'])
            ->setTranslations('slug', ['es' => 'otras-mercancias', 'en' => 'other-merchandise'])
            ->save();


        Sector::create([
            "id" => 2,
            "name" => 'Calçat',
            "slug" => 'calcat',
        ])->setTranslations('name', ['es' => 'Calzado', 'en' => 'Footwear'])
            ->setTranslations('slug', ['es' => 'calzado', 'en' => 'footwear'])
            ->save();


        Sector::create([
            "id" => 3,
            "name" => 'Complements Alimentaris',
            "slug" => 'complements-alimentaris',
        ])->setTranslations('name', ['es' => 'Complementos Alimentarios', 'en' => 'Food Supplements'])
            ->setTranslations('slug', ['es' => 'complementos-alimentarios', 'en' => 'food-supplements'])
            ->save();


        Sector::create([
            "id" => 4,
            "name" => 'Perfumeria',
            "slug" => 'perfumeria',
        ])->setTranslations('name', ['es' => 'Perfumeria', 'en' => 'Perfumery'])
            ->setTranslations('slug', ['es' => 'perfumeria', 'en' => 'perfumery'])
            ->save();


        Sector::create([
            "id" => 5,
            "name" => 'Tèxtil i Confecció',
            "slug" => 'textil-i-confeccio',
        ])->setTranslations('name', ['es' => 'Textil y Confección', 'en' => 'Textile and Clothing'])
            ->setTranslations('slug', ['es' => 'textil-y-confección', 'en' => 'textile-and-clothing'])
            ->save();


        Sector::create([
            "id" => 6,
            "name" => 'Tèxtil i Confecció/Alt. Merc.',
            "slug" => 'textil-i-confeccio-alt-merc',
        ])->setTranslations('name', ['es' => 'Textil y Confección/Alt.Mercado', 'en' => 'Textile and Clothing/Alt. Market'])
            ->setTranslations('slug', ['es' => 'textil-y-confeccion-alt-mercado', 'en' => 'textile-and-clothing-alt-market'])
            ->save();

        foreach ($services as $service) {
            $createdService = Service::create([
                'image' => $service['image'],
                'title' => $service['title']['es'], // Establece el idioma principal
                'slug' => $service['slug']['es'], // Establece el idioma principal
                'summary' => $service['summary']['es'], // Establece el idioma principal
                'description' => $service['description']['es'], // Establece el idioma principal
            ]);

            // Añade las traducciones para los otros idiomas
            $createdService
                ->setTranslations('title', $service['title'])
                ->setTranslations('slug', $service['slug'])
                ->setTranslations('summary', $service['summary'])
                ->setTranslations('description', $service['description'])
                ->save();
        }
    }
}
