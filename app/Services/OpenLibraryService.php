<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class OpenLibraryService {
    
    private const BASE_URL = 'https://openlibrary.org';

    /**
     * Busca libros en OpenLibrary por título y autor.
     */
    public function search(string $query, int $limit = 50): array
    { 
        try {
            usleep(100000); // 100ms para no saturar la API

            $response = Http::timeout(30)->get(self::BASE_URL . "/search.json", [
                'subject' => $query,
                'limit'   => min($limit, 100),
                'fields'  => 'key,title,author_name,isbn,number_of_pages_median,first_publish_year,subject,cover_i,first_sentence',
            ]);

            if ($response->failed()) {
                return [];
            }

            $books = [];
            foreach ($response->json()['docs'] ?? [] as $doc) {
                $book = $this->formatBook($doc);
                if ($this->isValidBook($book)) {
                    $books[] = $book;
                }
            }

            return $books;
        } catch (\Exception $e) {
            \Log::error('OpenLibraryService::search error: ' . $e->getMessage(), [
                'query' => $query,
                'limit' => $limit,
            ]);
            return [];
        }
    }

    /**
     * Valida que un libro tenga datos suficientes
     */
    private function isValidBook(array $book): bool {
        return !empty($book['title'])
            && !empty($book['author'])
            && !empty($book['pages'])
            && $book['pages'] > 0;
    }
    
    /**
     * Ontenemos detalles completos de un libro por su ID de OpenLLibrary.
     */
    public function getBook(string $openLibraryId): ?array {

        try {
            $response = Http::get(self::BASE_URL . "/works/{$openLibraryId}.json");

            if ($response -> failed()) {
                return null;
            }

            return $this -> formatBook($response -> json());
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Formatea un documento de búsqueda al formato de nuestra BD
     */
    private function formatBook(array $doc): array {
        return [
            'ol_id'          => $doc['key'] ?? null,
            'title'          => $doc['title'] ?? 'Desconocido',
            'author'         => $doc['author_name'][0] ?? $doc['authors'][0]['name'] ?? 'Desconocido',
            'isbn'           => $doc['isbn'][0] ?? null,
            'pages'          => $doc['number_of_pages_median'] ?? null,
            'published_year' => $doc['first_publish_year'] ?? null,
            'genre'          => $doc['subject'][0] ?? null,
            'cover_url'      => $this->getCoverUrl($doc['cover_i'] ?? null),
            'description'    => $doc['first_sentence'][0] ?? null,
        ];
    }

    /**
     * Formatea un documento detallado
     */
    private function formatBookDetails(array $doc): array {

        $coverUrl = null;
        if (isset($doc['covers']) && !empty($doc['covers'])) {
            $coverUrl = "https://covers.openlibrary.org/b/id/{$doc['covers'][0]}-M.jpg";
        }

        return [
            'title' => $doc['title'] ?? 'Desconocido',
            'author' => $doc['authors'][0]['name'] ?? 'Desconocido',
            'isbn' => $doc['isbn_10'][0] ?? null,  
            'pages' => $doc['number_of_pages'] ?? null,
            'published_year' => substr($doc['publish_date'] ?? '0000', 0, 4),
            'genre' => $doc['subjects'][0]['value'] ?? null,
            'cover_url' => $coverUrl,
            'description' => $doc['description']['value'] ?? null,
        ];
    }

    /**
     * Generar la URL de la portada
     */
    private function getCoverUrl(?int $coverId): ?string {

        if (!$coverId) {
            return null;
        }

        return "https://covers.openlibrary.org/b/id/{$coverId}-M.jpg";
    }
}