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
        $Directory = 'swoole_hyperf'
        $Directory_app = 'hyperf-skeleton/app'
        $Directory_bin = 'hyperf-skeleton/bin'
        $Directory_config = 'hyperf-skeleton/config'
        $Directory_test = 'hyperf-skeleton/test'
        $Directory_storage = 'hyperf-skeleton/storage'

        $Container = 'hyperf'
        # Git Pull
        '[Step_2] git -> swoole_hyperf'
        # cd ./$Directory
        # git branch
        # git fetch
        # git pull
        # cd ..
        # Container
        docker exec $Container bash -c "rm -r /$Directory_app && rm -r /$Directory_bin && rm -r /$Directory_config && rm -r /$Directory_test && rm -r /$Directory_storage"
        '[Step_3] remove directory -> app bin config test'
        docker cp ./$Directory/$Directory_app ${Container}:/$Directory_app
        docker cp ./$Directory/$Directory_bin ${Container}:/$Directory_bin
        docker cp ./$Directory/$Directory_config ${Container}:/$Directory_config
        docker cp ./$Directory/$Directory_test ${Container}:/$Directory_test
        docker cp ./$Directory/$Directory_storage ${Container}:/$Directory_storage
        '[Step_4] copy directory -> app bin config test'
        docker restart $Container
        '[Step_5] restart container -> hyperf'
        # Git Push
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
