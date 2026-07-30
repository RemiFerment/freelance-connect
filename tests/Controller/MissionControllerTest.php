<?php

// namespace App\Tests\Controller;

// use App\Entity\Mission;
// use Doctrine\ORM\EntityManagerInterface;
// use Doctrine\ORM\EntityRepository;
// use Symfony\Bundle\FrameworkBundle\KernelBrowser;
// use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

// final class MissionControllerTest extends WebTestCase
// {
//     private KernelBrowser $client;
//     private EntityManagerInterface $manager;

//     /** @var EntityRepository<Mission> */
//     private EntityRepository $missionRepository;
//     private string $path = '/client/';

//     protected function setUp(): void
//     {
//         $this->client = static::createClient();
//         $this->manager = static::getContainer()->get('doctrine')->getManager();
//         $this->missionRepository = $this->manager->getRepository(Mission::class);

//         foreach ($this->missionRepository->findAll() as $object) {
//             $this->manager->remove($object);
//         }

//         $this->manager->flush();
//     }

//     public function testIndex(): void
//     {
//         $this->client->followRedirects();
//         $crawler = $this->client->request('GET', $this->path);

//         self::assertResponseStatusCodeSame(200);
//         self::assertPageTitleContains('Mission index');

//         // Use the $crawler to perform additional assertions e.g.
//         // self::assertSame('Some text on the page', $crawler->filter('.p')->first()->text());
//     }

//     public function testNew(): void
//     {
//         $this->client->request('GET', sprintf('%snew', $this->path));

//         self::assertResponseStatusCodeSame(200);

//         $this->client->submitForm('Save', [
//             'mission[title]' => 'Testing',
//             'mission[description]' => 'Testing',
//             'mission[budget]' => 'Testing',
//             'mission[deadline]' => 'Testing',
//             'mission[createdAt]' => 'Testing',
//             'mission[updatedAt]' => 'Testing',
//             'mission[client]' => 'Testing',
//             'mission[freelance]' => 'Testing',
//             'mission[status]' => 'Testing',
//             'mission[language]' => 'Testing',
//             'mission[categories]' => 'Testing',
//         ]);

//         self::assertResponseRedirects('/client');

//         self::assertSame(1, $this->missionRepository->count([]));

//         $this->markTestIncomplete('This test was generated');
//     }

//     public function testShow(): void
//     {
//         $fixture = new Mission();
//         $fixture->setTitle('My Title');
//         $fixture->setDescription('My Title');
//         $fixture->setBudget('My Title');
//         $fixture->setDeadline('My Title');
//         $fixture->setCreatedAt('My Title');
//         $fixture->setUpdatedAt('My Title');
//         $fixture->setClient('My Title');
//         $fixture->setFreelance('My Title');
//         $fixture->setStatus('My Title');
//         $fixture->setLanguage('My Title');
//         $fixture->setCategories('My Title');

//         $this->manager->persist($fixture);
//         $this->manager->flush();

//         $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

//         self::assertResponseStatusCodeSame(200);
//         self::assertPageTitleContains('Mission');

//         // Use assertions to check that the properties are properly displayed.
//         $this->markTestIncomplete('This test was generated');
//     }

//     public function testEdit(): void
//     {
//         $fixture = new Mission();
//         $fixture->setTitle('Value');
//         $fixture->setDescription('Value');
//         $fixture->setBudget('Value');
//         $fixture->setDeadline('Value');
//         $fixture->setCreatedAt('Value');
//         $fixture->setUpdatedAt('Value');
//         $fixture->setClient('Value');
//         $fixture->setFreelance('Value');
//         $fixture->setStatus('Value');
//         $fixture->setLanguage('Value');
//         $fixture->setCategories('Value');

//         $this->manager->persist($fixture);
//         $this->manager->flush();

//         $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

//         $this->client->submitForm('Update', [
//             'mission[title]' => 'Something New',
//             'mission[description]' => 'Something New',
//             'mission[budget]' => 'Something New',
//             'mission[deadline]' => 'Something New',
//             'mission[createdAt]' => 'Something New',
//             'mission[updatedAt]' => 'Something New',
//             'mission[client]' => 'Something New',
//             'mission[freelance]' => 'Something New',
//             'mission[status]' => 'Something New',
//             'mission[language]' => 'Something New',
//             'mission[categories]' => 'Something New',
//         ]);

//         self::assertResponseRedirects('/client');

//         $fixture = $this->missionRepository->findAll();

//         self::assertSame('Something New', $fixture[0]->getTitle());
//         self::assertSame('Something New', $fixture[0]->getDescription());
//         self::assertSame('Something New', $fixture[0]->getBudget());
//         self::assertSame('Something New', $fixture[0]->getDeadline());
//         self::assertSame('Something New', $fixture[0]->getCreatedAt());
//         self::assertSame('Something New', $fixture[0]->getUpdatedAt());
//         self::assertSame('Something New', $fixture[0]->getClient());
//         self::assertSame('Something New', $fixture[0]->getFreelance());
//         self::assertSame('Something New', $fixture[0]->getStatus());
//         self::assertSame('Something New', $fixture[0]->getLanguage());
//         self::assertSame('Something New', $fixture[0]->getCategories());

//         $this->markTestIncomplete('This test was generated');
//     }

//     public function testRemove(): void
//     {
//         $fixture = new Mission();
//         $fixture->setTitle('Value');
//         $fixture->setDescription('Value');
//         $fixture->setBudget('Value');
//         $fixture->setDeadline('Value');
//         $fixture->setCreatedAt('Value');
//         $fixture->setUpdatedAt('Value');
//         $fixture->setClient('Value');
//         $fixture->setFreelance('Value');
//         $fixture->setStatus('Value');
//         $fixture->setLanguage('Value');
//         $fixture->setCategories('Value');

//         $this->manager->persist($fixture);
//         $this->manager->flush();

//         $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
//         $this->client->submitForm('Delete');

//         self::assertResponseRedirects('/client');
//         self::assertSame(0, $this->missionRepository->count([]));

//         $this->markTestIncomplete('This test was generated');
//     }
// }
