<?php
// src/Twig/AppExtension.php
namespace App\Twig;

use App\Entity\Like;
use App\Entity\News;
use App\Repository\LikeRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Doctrine\Persistence\ManagerRegistry;

class AppExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('get_ratio', [$this, 'getratio']),
        ];
    }
    public function getratio( $likes,News $article): int
    {
        $D=0;
        $L=0;
        foreach ($likes as $like)
        {
            if ( $like->getArticle()==$article)
            {
                if ($like->getStatus()=="L")
                    $L++;
                else
                    $D++;
            }
        }
        return $L-$D;
    }

}