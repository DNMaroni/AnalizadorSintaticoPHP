<?php

/*
PARA DEPOIS
ajustar regra dos ponteiros
criar switch e for
criar regra das structs
*/

$regras_sintatico = [
    //operadores e comparadores
    'OP' => [
        '+', '*', '-', '/', '%', '==', '!=', '>', '<', '>=', '<=', '!', '&&', '||'
    ],
    //operadores unários
    'OPU' => [
        '++', '--'
    ],
    //negação
    'NEG' => [
        '!'
    ],
    //operador de atribuição
    'OPA' => [
        '*=', '-=', '%=', '/=', '='
    ],
    'TIPOS' => [
        'int', 'char', 'float', 'double',
        [
            'MODIF', 'TIPOS'
        ]
    ],
    //modificadores
    'MODIF' => [
        'unsinged', 'singed', 'short', 'long', 'unsigned long', 'signed long', 'unsigned long', 'signed short', 'long double', 'signed short'
    ],
    //expressões
    'EXP' => [
        [
            'num', 'OP', 'num'
        ],
        'num',
        [
            '(', 'EXP', ')'
        ],
        [
            'id', 'OP', 'id'
        ],
        [
            'num', 'OP', 'id'
        ],
        [
            'id', 'OP', 'num'
        ],
        'id',
        'FUNC',
        [
            'OPU', 'id'
        ],
        [
            'id', 'OPU'
        ],
        [
            'NEG', 'id'
        ],
        [
            'EXP', 'OP', 'EXP'
        ]
    ],
    //declarações
    'DECL' => [
        [
            'TIPOS', 'id', '=', 'EXP', ';'
        ],
        [
            'TIPOS', 'ATRI'
        ]
    ],
    //atribuiçao
    'ATRI' => [
        [
            'id', 'OPA', 'id', ';'
        ],
        [
            'id', 'OPA', 'num', ';'
        ],
        [
            'id', ';'
        ],
        [
            'id', 'OP', 'id', ';'
        ],
        [
            'id', 'OPA', 'EXP', ';'
        ]
    ],
    'COMANDO' => [
        'ATRI',
        ';',
        'DECL',
        'IF',
    ],
    'RECUR' => [
        [
            'COMANDO', 'RECUR'
        ],
        'COMANDO',
        'RECUR'
    ],
    'BLOCO' => [
        [
            '{', 'RECUR', '}'
        ]
    ],
    'IF' => [
        [
            'if', '(', 'EXP', ')', 'BLOCO', 'else', 'BLOCO'
        ],
        [
            'if', '(', 'EXP', ')', 'BLOCO'
        ],
        [
            'if', 'EXP', 'BLOCO' //pode ser expressao, se tiver ()
        ]
    ],

];
