<?php

namespace App\Repository;

use App\Object\Note;
use Psr\Log\LoggerInterface;
use PDO;

/**
 * Class NoteRepository.
 */
class NoteRepository
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @var \PDO
     */
    private $pdo;

    /**
     * @param \Psr\Log\LoggerInterface $logger
     * @param \PDO                     $pdo
     */
    public function __construct(LoggerInterface $logger, PDO $pdo)
    {
        $this->logger = $logger;
        $this->pdo = $pdo;
    }

    /**
     * @return array (of \App\Object\Note)
     */
    public function getAllNotes()
    {
        $this->logger->info('NoteRpository: get all notes');

        $stmt = $this->pdo->prepare('SELECT * FROM notes ORDER BY id ASC');
        $stmt->execute();
        $result = array();
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $result[] = new Note($row['id'], $row['text']);
        }

        return $result;
    }

    /**
     * @param int $id
     *
     * @return bool|\App\Object\Note
     */
    public function getNote($id)
    {
        $this->logger->info('NoteRepository: get note');

        $stmt = $this->pdo->prepare('SELECT * FROM notes WHERE id = :id');
        $id = (int) $id;
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        if ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            return new Note($row['id'], $row['text']);
        }

        return false;
    }

    /**
     * @param int $id
     *
     * @return bool
     */
    public function deleteNote($id)
    {
        $this->logger->info('NoteRepository: delete note');

        $stmt = $this->pdo->prepare('DELETE FROM notes WHERE id = :id');
        $id = (int) $id;
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return true;
    }
}
