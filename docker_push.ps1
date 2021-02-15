# 設定權限
# VSCode -> 內容 -> 進階 -> 以系統管理員身分執行
# Get-ExecutionPolicy -List
# Set-ExecutionPolicy UnRestricted

# 執行
# ./docker_push.ps1 Tab鍵
param(
    [ValidateSet('hyperf', 'delete')]
    [string]$Container
)
"[Step_1] choose container -> $Container"
switch ($Container) {
    'hyperf' {
        $Container = 'hyperf'
        # Container
        docker restart hyperf
        '[Step_2] restart container -> hyperf'
        # docker exec hyperf bash -c 'rm -r /var/www/html/hyperf && ls'
        # '[Step_3] -> remove directory -> /var/www/html/hyperf'
        # docker cp ./hyperf hyperf:/var/www/html

        # docker exec swoole bash -c 'chown -R www-data:www-data /var/www/html && ls'

        # docker commit -a "Hank" -m "app" swoole asia.gcr.io/p2-stage/app
        # docker push asia.gcr.io/p2-stage/app
    }
    'Website' {
        $Directory = 'ali88_website'
        $Container = 'website'
        # Git
        '[Step_2] -> Git_Website'
        cd ./$Directory
        git branch
        git fetch
        git pull
        cd ..
        # Container
        docker restart $Container
        '[Step_3] -> Container_Website'
        docker exec $Container bash -c "rm -r $Directory && ls"
        '[Step_4] -> Remove_Directory'
        docker cp ./$Directory ${Container}:/var/www/html
        
        docker exec $Container bash -c 'chown -R www-data:www-data /var/www/html && ls'

        docker commit -a 'Hank' -m $Directory $Container asia.gcr.io/p2-stage/$Directory
        docker push asia.gcr.io/p2-stage/$Directory
    }
    Default {
        '[Step_2] -> No Param!!!'
        docker rmi $(docker images -q) -f
    }
}
# 最後執行命令的狀態
"[Error_Code] -> $lastExitCode"
"[Result] -> $?"
