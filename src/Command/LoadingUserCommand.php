<?php

namespace App\Command;

use App\Domain\Model\User;
use App\Domain\Model\Comment;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

class LoadingUserCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:import-fixtures-user')
            ->setDescription('Imports datas into User and Comment tables');
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
        $PhotoRepo = $em->getRepository('App:Photo');
        $UserRepo = $em->getRepository('App:User');
        $TrickRepo = $em->getRepository('App:Trick');

        # User
        $password='$2y$13$jw4vwhQk1ersGtP9ItWdfewQQn/7Yp7jTI7gTCenxK5tzPBG.UC3y';
        $user = $this->getDatas('User');
        foreach ($user['User'] as $reference => $column) {
            $user = new User();
            $user->setfirstName($column['firstname']);
            $user->setName($column['name']);
//            $user->setEnabled('true');
            $user->setEmail($column['email']);
            $user->setPassword($password);
            if (! is_null($column['photo'] )){
            $linkedPhoto = $PhotoRepo->findOneByAlt($column['photo']);
            $user->setPhoto($linkedPhoto);}
            $em->persist($user);
        }
        $em->flush();

        # Comments
        $comment = $this->getDatas('Comment');
        foreach ($comment['Comment'] as $reference => $column)
        {
            $comment = new Comment();
            $linkedUser = $UserRepo->findOneByEmail($column['author']);
            $comment->setAuthor($linkedUser);
            $date = \DateTime::createFromFormat('Y-m-d H:i:s', $column['dateCrea']);
            $comment->setDateCrea($date);
            $comment->setContent($column['content']);
            $linkedTrick = $TrickRepo->findOneBy(array('name'=>$column['trick']))   ;
            $comment->setTrick($linkedTrick);
            $em->persist($comment);
        }
        $em->flush();
    }
}
