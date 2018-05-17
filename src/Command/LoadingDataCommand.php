<?php

namespace App\Command;

use App\Domain\Model\Category;
use App\Domain\Model\Trick;
use App\Domain\Model\Photo;
use App\Domain\Model\Video;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

class LoadingDataCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:import-fixtures-data')
            ->setDescription('Imports datas into Category, Trick, Photo and Video tables');
    }

    public function getDatas($entity)
    {
        $fixturesPath = $this->getContainer()->getParameter('fixtures_directory');
        $fixtures = Yaml::parse(file_get_contents( $fixturesPath.'/Fixtures'.$entity.'.yml', true));
        return $fixtures;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $CatRepo = $em->getRepository('App:Category');
        $TrickRepo = $em->getRepository('App:Trick');

        # Categories
        $category = $this->getDatas('Category');
        foreach ($category['Category'] as $reference => $column) {
            $category = new Category();
            $category->setName($column['name']);
            $em->persist($category);
        }
        $em->flush();

        # Tricks
        $trick = $this->getDatas('Trick');
        foreach ($trick['Trick'] as $reference => $column)
        {
            $trick = new Trick();
            $trick->setName($column['name']);
            $trick->setDescription($column['description']);
            $linkedCat = $CatRepo->findOneBy(array('name'=> $column['category']));
            $trick->setCategory($linkedCat);
            $date = \DateTime::createFromFormat('Y-m-d H:i:s', $column['creation']);
            $trick->setCreatedAt($date);
            $em->persist($trick);
        }
        $em->flush();

        # Photos
        $photo = $this->getDatas('Photo');

        foreach ($photo['Photo'] as $reference => $column)
        {
            $photo = new Photo();
            $photo->setType($column['type']);
            $photo->setAlt($column['alt']);
            if (! is_null($column['trick'] )){
            $linkedTrick = $TrickRepo->findOneBy(array('name'=> $column['trick']));
            $photo->setTrick($linkedTrick);}
            $photo->setFileName($column['name']);
            $em->persist($photo);
        }
        $em->flush();

        # Videos
        $video = $this->getDatas('Video');

        foreach ($video['Video'] as $reference => $column)
        {
            $video = new Video();
            $video->setType($column['type']);
            $video->setIdentif($column['identif']);
            $linkedTrick = $TrickRepo->findOneBy(array('name'=> $column['trick']));
            $video->setTrick($linkedTrick);
            $em->persist($video);
        }
        $em->flush();
    }
}
