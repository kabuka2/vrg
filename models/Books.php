<?php

namespace app\models;
use yii\web\UploadedFile;
use yii\helpers\BaseStringHelper;
use app\models\Authors;
use Yii;

/**
 * This is the model class for table "books".
 *
 * @property int $id
 * @property string $name_book
 * @property string|null $short_description
 * @property string|null $image_path
 * @property int|null $publication_date
 */
class Books extends \yii\db\ActiveRecord
{

    public $image; 
    public $image_temp;
    public $hidden_image_status;
    public $oldRecord;
    public $select_authors_book = [];// form
    public $authors = [];



    public function __construct($config = [])
    {
        parent::__construct($config);
    }

    // clone old status
    public function afterFind() {
      $this->oldRecord = clone new self();
      return parent::afterFind();
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'books';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name_book','publication_date','short_description','select_authors_book'], 'required'],
            [['publication_date'], 'date', 'format' => 'dd.MM.yyyy' , 'skipOnEmpty'=> true],
            [['name_book'], 'string', 'max' => 255],
            [['hidden_image_status'] , 'integer' , 'max'=> 1],
            [['image'],'image' ,'extensions' => 'png, jpg', 'mimeTypes'=> ['image/jpeg','image/png'] , 'maxSize'=>  2 *1024 * 1024],
            [['short_description'], 'string', 'max' => 512],
            [['select_authors_book'],'default'],
        ];
    }
    /**
     * {@inheritdoc}
    */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name_book' => Yii::t('app', 'Name Book'),
            'short_description' => Yii::t('app', 'Short Description'),
            'image_path' => Yii::t('app', 'Image Path'),
            'publication_date' => Yii::t('app', 'Publication Date'),
            'publication_date' => Yii::t('app', 'Publication Date'),
            'select_authors_book' => Yii::t('app','Selecting the authors of the book'),
            'image'=> Yii::t('app' ,'Image'),
            'authors'=> Yii::t('app','Authors'),
        ];
    }

    public function beforeSave($data) {
        if (parent::beforeSave($data)) {
           return $this->publication_date = !empty($this->publication_date) ?  strtotime($this->publication_date): time() ;
        }
    }

    public function getAuthors()
    {
        return $this->hasMany(Authors::class, ['id' => 'id_author'])
            ->viaTable('pivot_table', ['id_book' => 'id']);
    }

    public function getPivotTable()
    {
        return $this->hasMany(PivotTable::class, ['id_book' => 'id']);
    }

    /**
        update table pivot_table
     **/
    public function authorsCrudBooks():void
    {
        if ( !empty($this->id) ) {
            $this->deletePivot();
            if ( is_array($this->select_authors_book) && !empty($this->select_authors_book) ) {
                $array_selected_authors = array_unique($this->select_authors_book);
                $array_selected_authors = array_values($array_selected_authors);
                $array = [];

                for ($i = 0; $i < count($array_selected_authors); $i++ ) {
                    if ( (int)$array_selected_authors[$i] > 0 ) {
                        $array[]=[ $this->id , (int)$array_selected_authors[$i] ];
                    }
                }

                if ( !empty($array) ) {
                    Yii::$app->db->createCommand()
                    ->batchInsert('pivot_table', ['id_book','id_author'] , $array )
                    ->execute();
                }
            }
        }
    }

    public function deletePivot()
    {
        PivotTable::deleteAll(['[[id_book]]' => $this->id]);
    }

    // check status photo
    public function checkStatusImage()
    {
        if ( isset($this->hidden_image_status) && ($this->hidden_image_status == 0 || $this->hidden_image_status == 1) ) {  //

            // if the picture is not deleted, check if a new one is loaded

            if ( $this->hidden_image_status == 1  ) { // there is an old picture

                if ( empty($this->image_temp) ) { // no loading
                    return true;
                } else {
                    return false;
                }

            } else if ( $this->hidden_image_status == 0 ) {  //picture deleted or a new picture

                if ( !empty($this->image_temp) ) { // download started

                    if ( !empty($this->oldRecord->image_path) ) { // check if the picture has been uploaded earlier

                        $this->deleteImage();// delete the old picture
                        $this->uploadPhoto(); // upload now
                        return true;

                    } else {
                        $this->uploadPhoto(); // upload now
                        return true;
                    }
                } else { // download did not start ,  deleted

                    if ( !empty($this->oldRecord->image_path) ) { // check if the picture has been uploaded earlier

                        $this->deleteImage();// delete the old picture
                        $this->image_path = '';
                        return true;
                    } else {
                        $this->image_path = '';
                        return true;
                    }
                }

            } else {
                return false;
            }

        } else {
            return false;
        }
    }



    public function deleteImage():bool
    {
        if ( !empty($this->image_path) ) {
            if ( file_exists(Yii::getAlias('@web_folder').$this->oldRecord->image_path)) {
                @unlink(Yii::getAlias('@web_folder').$this->oldRecord->image_path);
            }
            return true;
        }
        return false;
    }

    public function uploadPhoto()
    {
        if(!$this->validate() || empty($this->image_temp) ){
            return false;
        }

        $this->_checkOldFile();

        $folder_relative =  sprintf(
            '%s%s%s%s%s%s',
            Yii::$app->params['images_folder'],
            DELIMITER,
            Yii::$app->params['name_folder_books_image'],
            DELIMITER,
            md5(date('Y.m.d')),
            DELIMITER
        );

        $folder_absolute =  sprintf(
            '%s%s',
            Yii::getAlias('@web_folder'),
            $folder_relative
        );

        $this->_imageDirCreate($folder_absolute);

        $name_file = md5(sprintf('%s%s%s.%s',
            time(),
            $this->image_temp->baseName,
            rand(),
            $this->image_temp->extension
        ));
        $name_file = sprintf('%s.%s',$name_file,$this->image_temp->extension);

        $this->image_temp->saveAs(sprintf('%s%s',$folder_absolute,$name_file));

        return $this->image_path = $folder_relative.$name_file;
    }


    // check status photo
    private function _checkOldFile():bool
    {

       if ($this->hidden_image_status == 0 ) { // there is an old file
            return true;
       }

//           if ( !empty($this->oldRecord->image_path)) {
//
//
//
//
//           }
//
//
//
//       } else {  // there is not old file
//
//
//
//
//
//
//
//       }
//
//
//
//        if ( $this->hidden_image_status == 0 ) { // new image
//
//
//                if ( !empty($this->image_temp) ) { // download started
//
//                   // check if the picture has been uploaded earlier
////
////                        $this->deleteImage();// delete the old picture
////                        $this->uploadPhoto(); // upload now
////                        return true;
////
////                    } else {
////                        $this->uploadPhoto(); // upload now
////                        return true;
////                    }
//
//                } else { // download did not start ,  deleted
//
//                    if ( !empty($this->oldRecord->image_path) ) { // check if the picture has been uploaded earlier
//
//                        $this->deleteImage();// delete the old picture
//                        $this->image_path = '';
//                        return true;
//                    } else {
//                        $this->image_path = '';
//                        return true;
//                    }
//                }
//
//            } else {
//                return false;
//            }

    }



    /***
     * create folder for image
     * @param string $path
     * @return void
     **/

    private function _imageDirCreate(string $path):void
    {
        if ( !is_dir($path) ) {
            mkdir($path , 0755 ,true);
        }
    }

}
