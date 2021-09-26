    <?php 
    
    public function setMailDriverValue(Request $request)
    {
        $envFile = app()->environmentFilePath();
        $str = file_get_contents($envFile);
        $AllValues=$request->except('_token');
        foreach ($AllValues as $key => $part) {
            $envKey=$key;
            $values=$part;
            
            if( strpos($values, " ")){
                $values ='"'.$part.'"';
            }

        
            $str .= "\n"; // In case the searched variable is in the last line without \n
            $keyPosition = strpos($str, "{$envKey}=");
            $endOfLinePosition = strpos($str, "\n", $keyPosition);
            $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);


            // If key does not exist, add it
            if (!$keyPosition || !$endOfLinePosition || !$oldLine) {
                $str .= "{$envKey}={$values}\n";
            } else {
                $str = str_replace($oldLine, "{$envKey}={$values}", $str);
            }

        }
       
       
        $str = substr($str, 0, -1);
        file_put_contents($envFile, $str);
        
        Session::flash('success', 'Settings has been Updated');
        return redirect()->back();
    
    }