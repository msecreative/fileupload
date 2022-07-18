<?php include "inc/header.php"; 
    //include "lib/config.php";
    include "lib/Database.php";
    $db = new Database();
?>
            <div class="myform">
                <?php
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        $permited = array("jpg", "jpeg", "png", "gif");
                        $fileName = $_FILES['image']['name'];
                        $fileSize = $_FILES['image']['size'];
                        $fileTemp = $_FILES['image']['tmp_name'];
                        $div = explode('.', $fileName);
                        $fileExt = strtolower(end($div));
                        $uniqueImg = substr(md5(time()), 0, 10).'.'.$fileExt;
                        $folder = "uploads/".$uniqueImg;

                        if (empty($fileName)) {
                            echo "<span class='error'>Please Select any Image !</span>";
                            }elseif ($fileSize >1048567) {
                            echo "<span class='error'>Image Size should be less then 1MB!
                            </span>";
                            } elseif (in_array($fileExt, $permited) === false) {
                            echo "<span class='error'>You can upload only:-"
                            .implode(', ', $permited)."</span>";
                            }else{
                            move_uploaded_file($fileTemp, $folder);
                            $query = "INSERT INTO tbl_image (image) VALUES('$folder')";
                            $insert_rows = $db->insert($query);
                            if ($insert_rows) {
                                echo "<span class='success'>Image Inserted Successfully!!</span>";
                            }else{
                                echo "<span class='error'>Image Not Inserted Successfully!!</span>";
                            }
                        }
                    }
                ?>
                <form action="" method="POST" enctype="multipart/form-data">
                    <table>
                        <tr>
                            <td>Select Image</td>
                            <td><input type="file" name="image"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><input type="submit" value="Upload" name="submit"></td>
                        </tr>
                    </table>
                </form>
                <table>
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Action</th>
                    </tr>
                    <!-- Delete Image -->
                    <?php
                        if (isset($_GET['del'])) {
                            $id = $_GET['del'];
                            $sql = "SELECT * FROM tbl_image WHERE id=$id";
                            $getImg = $db->select($sql);
                            if ($getImg) {
                                while ($imgdata = $getImg->fetch_assoc()) {
                                $delimg = $imgdata['image'];
                                unlink($delimg);
                                }
                               }
                        $sql = "DELETE FROM tbl_image WHERE id=$id";
                        $del = $db->delete($sql);

                        if ($del) {
                            echo "<span class='success'>Image Deleted Successfully!!</span>";
                        }else{
                            echo "<span class='error'>Image Not Deleted Successfully!!</span>";
                        }
                    }
                    ?>
                    <?php
                        $query = "SELECT * FROM tbl_image";
                        $getImg = $db->select($query);
                        if ($getImg) {
                            $i=0;
                            while ($result = $getImg->fetch_assoc()) { $i++; ?>
                    <tr>
                        <td><?=$i?></td>
                        <td><img src="<?= $result['image']; ?>" height="50px" width="50px" alt=""></td>
                        <td><a href="?del=<?=$result['id']?>">Delete</a></td>
                    </tr>
                    <?php
                            }
                        }
                    ?>
                </table>
            </div>
<?php include"inc/footer.php"; ?>

