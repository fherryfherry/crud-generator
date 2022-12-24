<?php


namespace CrudGenerator\Commands;

use CrudGenerator\Helpers\CrudHelper;
use SuperFrameworkEngine\Commands\CommandArguments;
use SuperFrameworkEngine\Commands\OutputMessage;
use SuperFrameworkEngine\Helpers\ShellProcess;

class Crud
{
    use OutputMessage, CommandArguments;

    /**
     * @description Create table`s crud module
     * @command make:crud
     * @param $table
     * @param null $moduleName
     */
    public function run($table) {
        $arguments = func_get_args();
        $templateController = file_get_contents(__DIR__."/../Stubs/_Crud/Controllers/AdminController.php.stub");
        $templateForm = file_get_contents(__DIR__."/../Stubs/_Crud/Views/form.blade.php.stub");
        $templateIndex = file_get_contents(__DIR__."/../Stubs/_Crud/Views/index.blade.php.stub");

        $name = $this->getArgument($arguments, "name");
        if(!$name) {
            $this->warning("Argument --name is required");
            return false;
        }

        $alias = [];
        $alias['model'] = convert_snake_to_CamelCase($table, true);
        $alias['route_class'] = $table;
        $alias['class_name'] = convert_snake_to_CamelCase($table, true);
        $alias['module'] = $name;
        $alias['name_field'] = CrudHelper::nameField($table);
        $alias['view'] = $table;
        $alias['model_assign'] = $this->modelAssign($table);
        $alias['module_path'] = $table;
        $alias['th_list'] = $this->thList($table);
        $alias['td_list'] = $this->tdList($table);
        $alias['form_group'] = $this->formGroup($table);
        $alias['add_validate_rule'] = $this->makeValidationRule($table);
        $alias['th_total'] = $this->thTotal($table);
        $selectQuery = $this->selectQuery($table);
        $alias['select_query'] = $selectQuery[0];
        $alias['select_repository'] = $selectQuery[1];

        // Re make model
        ShellProcess::run("cd ".base_path()." && ".PHP_BINARY." super make:model --ignore-header");

        // Replace Controller
        $templateController = $this->replacer($alias, $templateController);
        $this->publishController($table, $templateController);

        // Replace Form
        $templateForm = $this->replacer($alias, $templateForm);
        $this->publishFormView($table, $templateForm);

        // Replace Index
        $templateIndex = $this->replacer($alias, $templateIndex);
        $this->publishIndexView($table, $templateIndex);

        // Create menu
        $this->addMenu($alias['module'], $alias['module_path']);

        // Compile
        ShellProcess::run("cd ".base_path()." && ".PHP_BINARY." super compile --ignore-header");

        $this->success("CRUD table `{$table}` has been created!");
    }

    private function selectQuery($table) {
        $columnList = db()->listColumn($table);
        $input = null;
        $repos = null;
        foreach($columnList as $column) {
            if(strtolower(substr($column,-3, 3)) == "_id") {
                $tableJoin = substr($column, 0, strpos($column, "_id"));
                $isHasTable = true;
                if (!db()->hasTable($tableJoin)) {
                    if (db()->hasTable($tableJoin . "s")) {
                        $tableJoin .= "s";
                        $isHasTable = true;
                    } else {
                        $isHasTable = false;
                    }
                }
                if($isHasTable) {
                    $modelClass = convert_snake_to_CamelCase($tableJoin, true);
                    $repos .= 'use App\Repositories\\'.$modelClass.'Repository;'."\n";
                    $input .= "\t".'$data["'.$tableJoin.'_list"] = '.$modelClass.'Repository::findAll();'."\n";
                }
            }
        }
        return [$input,$repos];
    }

    private function addMenu(string $name, string $modulePath)
    {
        // Add menu
        $menusId = db("admin_menus")->insert([
            "name"=> $name,
            "icon"=> "menu",
            "url"=> $modulePath,
            "sorting"=> db("admin_menus")->count() + 1,
            "parent_id"=> 0
        ]);

        // Insert permission
        $roleSA = db("roles")->where("name = 'Super Admin'")->first();
        if($roleSA) {
            db("role_permissions")->insert([
                "admin_menus_id"=> $menusId,
                "roles_id"=> $roleSA['id']
            ]);
        }
    }

    private function publishController(string $table, string $template)
    {
        $className = convert_snake_to_CamelCase($table, true);
        $fileName = "Admin".$className."Controller.php";
        file_put_contents(base_path("app/Modules/Admin/Controllers/".$fileName), $template);
    }

    private function publishFormView(string $table, string $template)
    {
        if(!file_exists(base_path("app/Modules/Admin/Views/" . $table))) {
            mkdir(base_path("app/Modules/Admin/Views/" . $table));
        }
        file_put_contents(base_path("app/Modules/Admin/Views/".$table."/form.blade.php"), $template);
    }

    private function publishIndexView(string $table, string $template)
    {
        if(!file_exists(base_path("app/Modules/Admin/Views/" . $table))) {
            mkdir(base_path("app/Modules/Admin/Views/" . $table));
        }
        file_put_contents(base_path("app/Modules/Admin/Views/".$table."/index.blade.php"), $template);
    }

    private function readableColumn(string $column) {
        $column = str_replace(["id_","_id"],"",$column);
        $text = ucwords(str_replace("_"," ",$column));
        return $text;
    }

    private function inputHtml(string $column, bool $focus = false)
    {
        $columnRead = $this->readableColumn($column);
        $inputPassword = ['password','sandi','pin'];
        $inputGender = ['gender','sex','jenis_kelamin'];
        $inputTextArea = ['isi','content','description','deskripsi'];
        $inputEmail = ['mail','email'];
        $inputPhone = ['telp','phone','hp','handphone'];
        $inputNumber = ['age','year','month','price','amount','qty','quantity'];
        $inputUpload = ['upload','file','attachment','download','lampiran','document'];
        $inputImage = ['image','photo','foto','gambar','icon','logo','picture','pict'];

        $input = null;
        $isFocus = $focus ? "autofocus" : "";

        if(strtolower(substr($column,-3, 3)) == "_id") {
            $tableJoin = substr($column,0, strpos($column,"_id"));
            $isHasTable = true;
            if(!db()->hasTable($tableJoin)) {
                if(db()->hasTable($tableJoin."s")) {
                    $tableJoin .= "s";
                    $isHasTable = true;
                } else {
                    $isHasTable = false;
                }
            }
            if($isHasTable) {
                $nameField = CrudHelper::nameField($tableJoin);
                $columnRead = $this->readableColumn($column);
                $input .= "<select class='form-control select2' name='{$column}' required>\n";
                $input .= "\t<option value=''>** Select a {$columnRead}</option>\n";
                $input .= "\t".'@foreach($'.$tableJoin.'_list as $item)'."\n";
                $input .= "\t".'<option {{isset($row)&&$row->'.$column.'==$item["id"]?"selected":null}} value="{{$item[\'id\']}}">{{$item[\''.$nameField.'\']}}</option>'."\n";
                $input .= "\t".'@endforeach'."\n";
                $input .= "</select>";
            }
        }

        foreach($inputImage as $name) {
            if(strpos($column, $name) !== false) {
                $input .= "\n".'<p>{!! show_image_html($row->'.$column.') !!}</p>';
                $input .= "\n".'<input type="file" accept=".jpg,.jpeg,.png,.gif" id="input-'.$column.'" class="form-control" name="'.$column.'"/>';
                $input .= "\n".'<div class="help-block">Format file allowed only: jpg,jpeg,png,gif</div>';
            }
        }

        foreach($inputUpload as $name) {
            if(strpos($column, $name) !== false) {
                $input .= "\n".'<p>{!! show_downloadable_link($row->'.$column.') !!}</p>';
                $input .= '<input type="file" accept=".jpg,.jpeg,.png,.gif,.zip,.rar,.doc,.docx,.xls,.xlsx" id="input-'.$column.'" class="form-control" name="'.$column.'"/>';
                $input .= "\n".'<div class="help-block">Format file allowed only: jpg,png,gif,zip,rar,doc,docx,xls,xlsx</div>';
            }
        }

        foreach ($inputPassword as $name) {
            if(strpos($column, $name) !== false) {
                $input = '<input type="password" '.$isFocus.' id="input-'.$column.'" class="form-control" name="'.$column.'" placeholder="Please leave empty if not changed!"/>';
            }
        }

        foreach ($inputGender as $name) {
            if(strpos($column, $name) !== false) {
                $input = '<select class="form-control" id="input-'.$column.'" name="'.$column.'" required>';
                $input .= '<option {{isset($row) && $row->'.$column.'=="Male" ? "selected": "" }} value="Male">Male</option>'."\n";
                $input .= '<option {{isset($row) && $row->'.$column.'=="Female" ? "selected": "" }} value="Female">Female</option>'."\n";
                $input .= '</select>';
            }
        }

        foreach ($inputTextArea as $name) {
            if(strpos($column, $name) !== false) {
                $input = '<textarea '.$isFocus.' id="input-'.$column.'" name="'.$column.'" class="form-control editor" required >{{ isset($row)?$row->'.$column.' : null }}</textarea>';
            }
        }

        foreach ($inputEmail as $name) {
            if(strpos($column, $name) !== false) {
                $input = '<input type="email" '.$isFocus.' id="input-'.$column.'" class="form-control" required name="'.$column.'" placeholder="Enter '.$columnRead.'" value="{{ isset($row) ? $row->'.$column.' : null }}"/>';
            }
        }

        foreach ($inputPhone as $name) {
            if(strpos($column, $name) !== false) {
                $input = '<input type="number" '.$isFocus.' id="input-'.$column.'" class="form-control" required name="'.$column.'" placeholder="Enter '.$columnRead.'" value="{{ isset($row) ? $row->'.$column.' : null }}"/>';
            }
        }

        foreach ($inputNumber as $name) {
            if(strpos($column, $name) !== false) {
                $input = '<input type="number" '.$isFocus.' min="0" id="input-'.$column.'" class="form-control" required name="'.$column.'" placeholder="Enter '.$columnRead.'" value="{{ isset($row) ? $row->'.$column.' : null }}"/>';
            }
        }

        if(!$input) {
            $input = '<input type="text" '.$isFocus.' id="input-'.$column.'" class="form-control" required name="'.$column.'" placeholder="Enter '.$columnRead.'" value="{{ isset($row) ? $row->'.$column.' : null }}"/>';
        }

        return $input;
    }

    private function formGroup(string $table)
    {
        $except = ['id','created_at','deleted_at','updated_at','password'];

        $columns = db()->listColumn($table);
        $e = 0;
        $html = "";
        foreach($columns as $column) {
            $columnRead = $this->readableColumn($column);
            if(!in_array($column,$except)) {
                $input = $this->inputHtml($column, ($e==0) );
                $template = file_get_contents(__DIR__."/../Stubs/_Crud/Views/form_group_template.blade.php.stub");
                $template = str_replace(["{column}","{columnRead}","{input}"],[$column,$columnRead,$input],$template);
                $html .= $template."\n";
                $e++;
            }
        }
        return $html;
    }

    private function makeValidationRule(string $table)
    {
        $except = ['id','created_at','deleted_at','updated_at','password'];
        $columns = db()->listColumn($table);
        $html = [];
        foreach($columns as $column) {
            if(!in_array($column,$except)) {
                $html[$column] = "required";
            }
        }
        return var_min_export($html, true);
    }

    private function modelAssign(string $table)
    {
        $except = ['id','created_at','deleted_at','updated_at','password'];
        $columns = db()->listColumn($table);
        $html = '';
        foreach($columns as $column) {
            if(!in_array($column,$except)) {
                $html .= "\t\t\t\t".'$data->'.$column.' = request("'.$column.'");'."\n";
            }
        }
        return $html;
    }

    private function thTotal(string $table)
    {
        $except = ['id','deleted_at','updated_at','password'];
        $columns = db()->listColumn($table);
        $totalCols = 0;
        foreach($columns as $column) {
            if(!in_array($column,$except) && substr($column, -3, 3) != "_id") {
                $totalCols += 1;
            }
        }

        // Plus Action column & check box bulk
        $totalCols += 2;

        return $totalCols;
    }

    private function thList(string $table)
    {
        $except = ['id','deleted_at','updated_at','password'];
        $columns = db()->listColumn($table);
        $html = "";
        foreach($columns as $column) {
            $columnRead = $this->readableColumn($column);
            if(!in_array($column,$except) && substr($column, -3, 3) != "_id") {
                $html .= "\t\t\t\t\t".'<th>'.$columnRead.'</th>'."\n";
            }
        }
        return $html;
    }

    private function tdList(string $table)
    {
        $except = ['id','deleted_at','updated_at','password'];
        $descFields = ['description','content','isi','deskripsi'];
        $columns = db()->listColumn($table);
        $html = "";
        foreach($columns as $column) {
            if(!in_array($column,$except) && substr($column, -3, 3) != "_id") {
                $isAlready = false;
                foreach($descFields as $field) {
                    if(stripos($column,$field) !== false) {
                        $html .= "\t\t\t\t\t\t".'<td>{{substr(strip_tags($row["'.$column.'"]),0,60)}}...</td>'."\n";
                        $isAlready = true;
                    }
                }
                if(!$isAlready) {
                    switch ($column) {
                        case "created_at":
                            $html .= "\t\t\t\t\t\t".'<td>{!!date("d M Y",strtotime($row["'.$column.'"]))."<br/>".date("H:i",strtotime($row["'.$column.'"]))!!}</td>'."\n";
                            break;
                        default:
                            $html .= "\t\t\t\t\t\t".'<td>{{$row["'.$column.'"]}}</td>'."\n";
                            break;
                    }

                }
            }
        }
        return $html;
    }

    private function replacer(array $alias, $template)
    {
        $keys = array_keys($alias);
        foreach($keys as $i=>$key) {
            $keys[$i] = "{".$key."}";
        }
        $keys = array_values($keys);

        $values = array_values($alias);

        return str_replace($keys, $values, $template);
    }
}