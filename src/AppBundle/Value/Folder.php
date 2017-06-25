<?php

namespace AppBundle\Value;

class Folder
{
    const INBOX = 'inbox';
    const DONE = 'done';
    const PINNED = 'pinned';
    const TAGS = 'tags';

    const FOLDER_MAP = [
        self::INBOX => [
            DocumentState::PINNED,
            DocumentState::PENDING
        ],
        self::DONE => [
            DocumentState::DONE
        ],
        self::PINNED => [
            DocumentState::PINNED
        ]
    ];
}