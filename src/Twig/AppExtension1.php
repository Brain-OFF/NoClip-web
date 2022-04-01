<?php
// src/Twig/AppExtension.php
namespace App\Twig;

use App\Entity\Games;
use App\Entity\Like;
use App\Entity\News;
use App\Repository\LikeRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Doctrine\Persistence\ManagerRegistry;

class AppExtension1 extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('get_ratio1', [$this, 'get_ratio1']),
        ];
    }
    public function get_ratio1( $ratings,Games $game): float
    {
        $R=0;
        $C=0;
        if (!empty($ratings))
        {
            foreach ($ratings as $rating) {
                if ($rating->getIdgame() == $game) {
                    $C++;
                    $R += $rating->getNote();

                }
            }
            return $R;
        }
        return 0;
    }


}