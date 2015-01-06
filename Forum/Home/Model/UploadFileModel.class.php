<?php
namespace Home\Model;
use Think\Model;
class UploadFileModel extends Model{
	/**
	 * 文件查看
	 */
	public function FileManager(){
		
	}
	public function FileDelete(){
		
	}
	public function FileMove(){
		
	}
	/**
	 * 遍历获取目录下的指定类型的文件
	 * @param $path
	 * @param array $files
	 * @return array
	 */
	public function getfiles( $path , &$files = array() )
	{
		if ( !is_dir( $path ) ) return null;
		$handle = opendir( $path );
		while ( false !== ( $file = readdir( $handle ) ) ) {
			if ( $file != '.' && $file != '..' ) {
				$path2 = $path . '/' . $file;
				if ( is_dir( $path2 ) ) {
					getfiles( $path2 , $files );
				} else {
					if ( preg_match( "/\.(gif|jpeg|jpg|png|bmp)$/i" , $file ) ) {
						$files[] = $path2;
					}
				}
			}
		}
		return $files;
	}
	
}
?>