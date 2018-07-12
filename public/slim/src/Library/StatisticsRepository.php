<?php
namespace App\Library;

class StatisticsRepository extends Repository {

    public function toiletCount() {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) as count FROM toilets_data');
        $stmt->execute();

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result['count'];
    }

    public function newToiletCount() {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) as count FROM toilets_data WHERE added_by <> 0');
        $stmt->execute();

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result['count'];
    }

    public function descriptionCount() {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) as count FROM toilets_data WHERE description <> \'\'');
        $stmt->execute();

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result['count'];
    }

    public function pictureCount() {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) as count_pictures, COUNT(DISTINCT toilet_id) as count_toilets FROM pictures');
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function rateCount() {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) as count_rates, COUNT(DISTINCT toilet_id) as count_toilets FROM rates');
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function commentCount() {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) as count_comments, COUNT(DISTINCT toilet_id) as count_toilets FROM comments');
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }


    public function newToiletCountFromTo($from, $to) {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) as count FROM toilets_data WHERE added_by <> 0 AND postdate >= :date_start AND postdate <= :date_end');
        
        $fromTime = $from->format('Y-m-d') . ' 00:00:00';
        $toTime = $to->format('Y-m-d') . ' 00:00:00';

        $stmt->bindParam(':date_start', $fromTime);
        $stmt->bindParam(':date_end', $toTime);
        $stmt->execute();

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result['count'];
    }

    public function descriptionCountFromTo($from, $to) {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) as count FROM toilets_data WHERE description <> \'\' AND postdate >= :date_start AND postdate <= :date_end');
        
        $fromTime = $from->format('Y-m-d') . ' 00:00:00';
        $toTime = $to->format('Y-m-d') . ' 00:00:00';

        $stmt->bindParam(':date_start', $fromTime);
        $stmt->bindParam(':date_end', $toTime);
        $stmt->execute();

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result['count'];
    }

    public function pictureCountFromTo($from, $to) {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) as count_pictures, COUNT(DISTINCT toilet_id) as count_toilets FROM pictures WHERE postdate >= :date_start AND postdate <= :date_end');
        
        $fromTime = $from->format('Y-m-d') . ' 00:00:00';
        $toTime = $to->format('Y-m-d') . ' 00:00:00';

        $stmt->bindParam(':date_start', $fromTime);
        $stmt->bindParam(':date_end', $toTime);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function rateCountFromTo($from, $to) {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) as count_rates, COUNT(DISTINCT toilet_id) as count_toilets FROM rates WHERE postdate >= :date_start AND postdate <= :date_end');
        
        $fromTime = $from->format('Y-m-d') . ' 00:00:00';
        $toTime = $to->format('Y-m-d') . ' 00:00:00';

        $stmt->bindParam(':date_start', $fromTime);
        $stmt->bindParam(':date_end', $toTime);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function commentCountFromTo($from, $to) {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) as count_comments, COUNT(DISTINCT toilet_id) as count_toilets FROM comments WHERE postdate >= :date_start AND postdate <= :date_end');
        
        $fromTime = $from->format('Y-m-d') . ' 00:00:00';
        $toTime = $to->format('Y-m-d') . ' 00:00:00';

        $stmt->bindParam(':date_start', $fromTime);
        $stmt->bindParam(':date_end', $toTime);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

};