<?php
namespace Album\Model;

use Album\Model\Album;
use Laminas\Db\TableGateway\TableGatewayInterface;
use RuntimeException;

class AlbumTable{

    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway){
        $this->tableGateway=$tableGateway;
    }
    public function fetchAll(){
        $data= $this->tableGateway->select();
        return $data;
    }

    public function getAlbum($id){
        $id=(int) $id;
        $formset = $this->tableGateway->select(['id'=>$id]);
        $row = $formset->current();
        if(!$row){
            throw new RuntimeException(
                sprintf("No Record %d", $id)
            );
        }
        return $row;
    }
    public function saveAlbum(Album $album){
        $data =[
            'title' =>$album->title,
            'artist' =>$album->artist,
        
        ];

        $id = (int) $album->id;
        if ($id === 0){
            $this->tableGateway->insert($data);
            return;
        }
        try{
            $this->getAlbum($id);
        } catch(RuntimeException $e){
            throw new RuntimeException(
                sprintf("Update Invalid %d", $id)
            );
        }
        $this->tableGateway->update($data, ['id'=>$id]);
    }
    public function deleteAlbum($id){
        $this->tableGateway->delete(['id'=>(int) $id]);
    }
}