<?php

namespace Database\Seeders;

use App\Models\AskedQuestion;
use Illuminate\Database\Seeder;

class AskedQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $asked = [
            [
                'question' => 'What is Eatery?',
                'answer' => "This is the first item's accordion body. It is shown by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the .accordion-body, though the transition does limit overflow.",
            ],
            [
                'question' => 'What device does Eatery support?',
                'answer' => "This is the second item's accordion body. It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the .accordion-body, though the transition does limit overflow.",
            ],
            [
                'question' => 'How can I use Eatery?',
                'answer' => "This is the first item's accordion body. It is shown by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the .accordion-body, though the transition does limit overflow.",
            ],
        ];

        foreach ($asked as $ask) {
            AskedQuestion::updateOrCreate($ask);
        }
    }
}
