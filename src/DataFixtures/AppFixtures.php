<?php
namespace App\DataFixtures;

use App\Factory\EmployeeFactory;
use App\Factory\ProjectFactory;
use App\Factory\TaskFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $employees = EmployeeFactory::new ()->many(5)->create();

        $project1 = ProjectFactory::new ()->create(['name' => 'Projet 1', 'archive' => false]);
        $project2 = ProjectFactory::new ()->create(['name' => 'Projet 2', 'archive' => false]);

        //Tâches du Projet  1
        TaskFactory::new ()->create([
            'name'    => 'Tache 1 projet 1',
            'project' => $project1,
            'status'  => 'todo',
        ]);

        TaskFactory::new ()->create([
            'name'    => 'Tache 2 projet 1',
            'project' => $project1,
            'status'  => 'doing',
        ]);

        TaskFactory::new ()->create([
            'name'    => 'Tache 3 projet 1',
            'project' => $project1,
            'status'  => 'done',
        ]);

        //Tâches du Projet  2
        TaskFactory::new ()->create([
            'name'    => 'Tache 1 projet 2',
            'project' => $project2,
            'status'  => 'todo',
        ]);

        TaskFactory::new ()->create([
            'name'    => 'Tache 2 projet 2',
            'project' => $project2,
            'status'  => 'doing',
        ]);

        TaskFactory::new ()->create([
            'name'    => 'Tache 3 projet 2',
            'project' => $project2,
            'status'  => 'done',
        ]);
    }
}
