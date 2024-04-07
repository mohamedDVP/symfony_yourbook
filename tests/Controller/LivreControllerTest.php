<?php

namespace App\Test\Controller;

use App\Entity\Livre;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LivreControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/livre/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Livre::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Livre index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'livre[titre]' => 'Testing',
            'livre[isbn]' => 'Testing',
            'livre[description]' => 'Testing',
            'livre[archive]' => 'Testing',
            'livre[imageName]' => 'Testing',
            'livre[imageSize]' => 'Testing',
            'livre[updatedAt]' => 'Testing',
            'livre[auteur]' => 'Testing',
            'livre[editeur]' => 'Testing',
            'livre[genres]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Livre();
        $fixture->setTitre('My Title');
        $fixture->setIsbn('My Title');
        $fixture->setDescription('My Title');
        $fixture->setArchive('My Title');
        $fixture->setImageName('My Title');
        $fixture->setImageSize('My Title');
        $fixture->setUpdatedAt('My Title');
        $fixture->setAuteur('My Title');
        $fixture->setEditeur('My Title');
        $fixture->setGenres('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Livre');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Livre();
        $fixture->setTitre('Value');
        $fixture->setIsbn('Value');
        $fixture->setDescription('Value');
        $fixture->setArchive('Value');
        $fixture->setImageName('Value');
        $fixture->setImageSize('Value');
        $fixture->setUpdatedAt('Value');
        $fixture->setAuteur('Value');
        $fixture->setEditeur('Value');
        $fixture->setGenres('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'livre[titre]' => 'Something New',
            'livre[isbn]' => 'Something New',
            'livre[description]' => 'Something New',
            'livre[archive]' => 'Something New',
            'livre[imageName]' => 'Something New',
            'livre[imageSize]' => 'Something New',
            'livre[updatedAt]' => 'Something New',
            'livre[auteur]' => 'Something New',
            'livre[editeur]' => 'Something New',
            'livre[genres]' => 'Something New',
        ]);

        self::assertResponseRedirects('/livre/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getTitre());
        self::assertSame('Something New', $fixture[0]->getIsbn());
        self::assertSame('Something New', $fixture[0]->getDescription());
        self::assertSame('Something New', $fixture[0]->getArchive());
        self::assertSame('Something New', $fixture[0]->getImageName());
        self::assertSame('Something New', $fixture[0]->getImageSize());
        self::assertSame('Something New', $fixture[0]->getUpdatedAt());
        self::assertSame('Something New', $fixture[0]->getAuteur());
        self::assertSame('Something New', $fixture[0]->getEditeur());
        self::assertSame('Something New', $fixture[0]->getGenres());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Livre();
        $fixture->setTitre('Value');
        $fixture->setIsbn('Value');
        $fixture->setDescription('Value');
        $fixture->setArchive('Value');
        $fixture->setImageName('Value');
        $fixture->setImageSize('Value');
        $fixture->setUpdatedAt('Value');
        $fixture->setAuteur('Value');
        $fixture->setEditeur('Value');
        $fixture->setGenres('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/livre/');
        self::assertSame(0, $this->repository->count([]));
    }
}
