<li>
              
                  
                  <?php if(!isset($_POST['command']) || $Error_flag): ?>
                  <input type="text" name="category" value="" />
                  
                  <?php elseif(isset($_POST['command']) && !$Error_flag): ?>
                    <input type="text" name="category" value="<?= $display[0][1] ?>" />
                  <input type="hidden" name="categoryId" value="<?= $display[0][0] ?>" />
                  <?php endif ?> 
              
              <button type="submit" name="command" form="Form" value="Update">Update</button>
              <button type="submit" name="command" form="Form" value="Delete">Delete</button>
            </li>     